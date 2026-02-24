@extends('layouts.app-admin')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container" style="padding:20px;">
    <h1><i class="fas fa-ticket-alt"></i> Buat Pemesanan (Admin)</h1>
    <p class="text-muted">Alur pemesanan meniru modul customer: cari jadwal → pilih → pilih kursi → konfirmasi → bayar → e-ticket</p>

    <div class="card mt-3">
        <div class="card-body">
            <form id="adminBookingForm">
                <!-- Data Customer -->
                <div class="mb-4">
                    <h5>Data Customer</h5>
                    <div class="mb-2">
                        <label>Pilih Customer atau Buat Baru</label>
                        <select class="form-control" id="customerSelect">
                            <option value="">-- Cari Customer --</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" data-name="{{ $customer->name }}" data-phone="{{ $customer->phone }}" data-email="{{ $customer->email }}">{{ $customer->name }} ({{ $customer->phone }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Nama Lengkap <span style="color:red">*</span></label>
                            <input type="text" id="namaPemesan" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Nomor Handphone <span style="color:red">*</span></label>
                            <input type="tel" id="teleponPemesan" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label>Email <span style="color:red">*</span></label>
                        <input type="email" id="emailPemesan" class="form-control" required>
                    </div>
                </div>

                <!-- Pilih Jadwal -->
                <div class="mb-4">
                    <h5>Pilih Jadwal Perjalanan</h5>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Rute <span style="color:red">*</span></label>
                            <select id="ruteSelect" class="form-control" onchange="loadJadwal()">
                                <option value="">-- Pilih Rute --</option>
                                @foreach($rutes as $rute)
                                <option value="{{ $rute->id }}">{{ $rute->kota_asal }} → {{ $rute->kota_tujuan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Tanggal <span style="color:red">*</span></label>
                            <input type="date" id="tanggalKeberangkatan" class="form-control" onchange="loadJadwal()">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label>Jadwal (Waktu & Shuttle) <span style="color:red">*</span></label>
                        <select id="jadwalSelect" class="form-control" onchange="loadJadwalDetails()">
                            <option value="">-- Pilih Jadwal --</option>
                        </select>
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="loadSeatMap()">Tampilkan Peta Kursi</button>
                            <div id="seatMap" style="margin-top:12px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Jumlah Penumpang -->
                <div class="mb-4">
                    <h5>Jumlah Penumpang</h5>
                    <div class="mb-2">
                        <input type="number" id="jumlahPenumpang" class="form-control" min="1" max="10" value="1" onchange="loadPenumpangForm(); updateSummary();">
                    </div>
                </div>

                <div id="detailPenumpangContainer"></div>

                <!-- Promo -->
                <div class="mb-4">
                    <h5>Kode Promo (Opsional)</h5>
                    <div class="row">
                        <div class="col-md-8 mb-2">
                            <input type="text" id="kodePromo" class="form-control" placeholder="Masukkan kode promo">
                        </div>
                        <div class="col-md-4 mb-2">
                            <button type="button" class="btn btn-warning w-100" onclick="validatePromo()">Validasi</button>
                        </div>
                    </div>
                    <div id="promoResult" style="display:none; margin-top:10px; padding:10px; border-radius:6px;"></div>
                </div>

                <!-- Catatan -->
                <div class="mb-4">
                    <h5>Catatan (Opsional)</h5>
                    <div class="mb-2">
                        <textarea id="catatan" class="form-control" rows="3" placeholder="Masukkan catatan tambahan jika ada..."></textarea>
                    </div>
                </div>

                <!-- Summary -->
                <div class="mb-4 p-3" style="background:#f8f9fa; border-radius:6px;">
                    <div class="d-flex justify-content-between"><span>Total Penumpang:</span><strong id="summaryPenumpang">0 Orang</strong></div>
                    <div class="d-flex justify-content-between"><span>Subtotal:</span><strong id="summarySubtotal">Rp 0</strong></div>
                    <div class="d-flex justify-content-between mt-2"><span>Total Bayar:</span><strong id="summaryTotal">Rp 0</strong></div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
                    <button type="button" class="btn btn-primary" onclick="submitAdminBooking()">Lanjutkan Pemesanan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Reuse same JS logic as modal but adapted for page (endpoints prefixed with /admin/api/...)

// Handle customer selection
if(document.getElementById('customerSelect')){
    document.getElementById('customerSelect').addEventListener('change', function(){
        if(this.value){
            const option = this.options[this.selectedIndex];
            document.getElementById('namaPemesan').value = option.dataset.name || '';
            document.getElementById('teleponPemesan').value = option.dataset.phone || '';
            document.getElementById('emailPemesan').value = option.dataset.email || '';
        }
    });
}

function loadJadwal(){
    const rute_id = document.getElementById('ruteSelect').value;
    const tanggal = document.getElementById('tanggalKeberangkatan').value;

    if(!rute_id || !tanggal){
        document.getElementById('jadwalSelect').innerHTML = '<option value="">-- Pilih Jadwal --</option>';
        return;
    }

    fetch(`/admin/api/jadwal?rute_id=${rute_id}&tanggal=${tanggal}`)
        .then(r => r.json())
        .then(data => {
            let options = '<option value="">-- Pilih Jadwal --</option>';
            if(data.jadwal && data.jadwal.length){
                data.jadwal.forEach(j => {
                    options += `<option value="${j.id}" data-harga="${j.harga_total}" data-shuttle="${j.shuttle.nama_shuttle}">${j.waktu_keberangkatan} - ${j.shuttle.nama_shuttle} (${j.kursi_tersedia} kursi)</option>`;
                });
            } else {
                options = '<option value="">-- Tidak ada jadwal tersedia --</option>';
            }
            document.getElementById('jadwalSelect').innerHTML = options;
            document.getElementById('jadwalSelect').addEventListener('change', updateSummary);
        });
}

function loadJadwalDetails(){ updateSummary(); }

function loadPenumpangForm(){
    const jumlah = parseInt(document.getElementById('jumlahPenumpang').value) || 1;
    let html = '';
    for(let i=1;i<=jumlah;i++){
        html += `
        <div class="card mb-3 p-3">
            <h6>Penumpang ${i}</h6>
            <div class="row">
                <div class="col-md-5 mb-2"><input class="form-control penumpang-nama" placeholder="Nama lengkap" data-index="${i}" required></div>
                <div class="col-md-4 mb-2"><input class="form-control penumpang-nik" placeholder="NIK (16)" maxlength="16" required></div>
                <div class="col-md-3 mb-2"><input class="form-control penumpang-nomor-kursi" placeholder="Kursi" readonly></div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <select class="form-control penumpang-jk" required>
                        <option value="">-- Jenis Kelamin --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6 mb-2"><input class="form-control penumpang-telepon" placeholder="0812..." required></div>
            </div>
            <div class="mb-2"><input class="form-control penumpang-email" placeholder="email@contoh.com" required></div>
        </div>
        `;
    }
    document.getElementById('detailPenumpangContainer').innerHTML = html;
    document.getElementById('summaryPenumpang').textContent = jumlah + ' Orang';
    updateSummary();
}

// Seat map rendering and selection
function loadSeatMap(){
    const jadwalSelect = document.getElementById('jadwalSelect');
    if(!jadwalSelect || jadwalSelect.selectedIndex<=0) { alert('Pilih jadwal terlebih dahulu'); return; }
    const jadwalId = jadwalSelect.value;
    const kapasitas = parseInt(jadwalSelect.options[jadwalSelect.selectedIndex].dataset.kapasitas) || 0;
    if(!kapasitas) { document.getElementById('seatMap').innerHTML = '<div class="text-muted">Info kapasitas kursi tidak tersedia</div>'; return; }

    fetch(`/admin/api/jadwal/${jadwalId}/kursi`)
        .then(r=>r.json())
        .then(data=>{
            const taken = data.taken || [];
            let mapHtml = '<div style="display:flex;flex-wrap:wrap;gap:8px;">';
            for(let s=1;s<=kapasitas;s++){
                const isTaken = taken.includes(String(s)) || taken.includes(s);
                mapHtml += `<div class="seat ${isTaken? 'taken' : 'available'}" data-seat="${s}" style="width:52px;height:44px;border:1px solid #ccc;border-radius:6px;display:flex;align-items:center;justify-content:center;cursor:${isTaken? 'not-allowed':'pointer'};background:${isTaken? '#f8d7da':'#fff'};">${s}</div>`;
            }
            mapHtml += '</div>';
            document.getElementById('seatMap').innerHTML = mapHtml;

            // Attach click handlers
            document.querySelectorAll('#seatMap .seat').forEach(el=>{
                el.addEventListener('click', function(){
                    if(this.classList.contains('taken')) return;
                    const seat = this.dataset.seat;
                    if(this.classList.contains('selected')){
                        // deselect and remove from passenger
                        this.classList.remove('selected');
                        document.querySelectorAll('.penumpang-nomor-kursi').forEach(inp=>{
                            if(inp.value == seat) inp.value = '';
                        });
                    } else {
                        // assign to first empty passenger seat
                        const inputs = document.querySelectorAll('.penumpang-nomor-kursi');
                        let assigned = false;
                        for(let i=0;i<inputs.length;i++){
                            if(!inputs[i].value){
                                inputs[i].value = seat;
                                this.classList.add('selected');
                                assigned = true;
                                break;
                            }
                        }
                        if(!assigned){ alert('Semua penumpang sudah memiliki kursi. Hapus salah satu untuk mengganti.'); }
                    }
                });
            });
        })
        .catch(e=>{ console.error(e); alert('Gagal memuat peta kursi'); });
}

function validatePromo(){
    const kode = document.getElementById('kodePromo').value;
    if(!kode){ alert('Masukkan kode promo'); return; }
    fetch(`/admin/api/promo/validate?kode=${kode}`)
        .then(r=>r.json())
        .then(data=>{
            const res = document.getElementById('promoResult');
            if(data.valid){ res.style.display='block'; res.style.background='#d4edda'; res.style.color='#155724'; res.innerHTML = `Promo valid: ${data.nama} (${data.diskon}%)`; res.dataset.valid='true'; res.dataset.diskon=data.diskon; }
            else { res.style.display='block'; res.style.background='#f8d7da'; res.style.color='#721c24'; res.innerHTML = data.message || 'Kode promo tidak valid'; res.dataset.valid='false'; res.dataset.diskon='0'; }
            updateSummary();
        });
}

function updateSummary(){
    const jadwal = document.getElementById('jadwalSelect');
    if(!jadwal || jadwal.selectedIndex<=0){ document.getElementById('summarySubtotal').textContent='Rp 0'; document.getElementById('summaryTotal').textContent='Rp 0'; return; }
    const opt = jadwal.options[jadwal.selectedIndex];
    const harga = parseFloat(opt.dataset.harga) || 0;
    const jumlah = parseInt(document.getElementById('jumlahPenumpang').value) || 0;
    if(harga<=0 || jumlah<=0){ document.getElementById('summarySubtotal').textContent='Rp 0'; document.getElementById('summaryTotal').textContent='Rp 0'; return; }
    const subtotal = harga * jumlah;
    const diskonPercent = parseFloat(document.getElementById('promoResult').dataset.diskon || 0);
    const diskon = (subtotal * diskonPercent)/100;
    const total = subtotal - diskon;
    document.getElementById('summarySubtotal').textContent = 'Rp ' + Math.round(subtotal).toLocaleString('id-ID');
    document.getElementById('summaryTotal').textContent = 'Rp ' + Math.round(total).toLocaleString('id-ID');
}

function submitAdminBooking(){
    const form = document.getElementById('adminBookingForm');
    // basic validity
    const jumlah = parseInt(document.getElementById('jumlahPenumpang').value) || 0;
    if(!form.checkValidity() || jumlah<=0){ alert('Mohon isi semua field yang diperlukan'); return; }

    const penumpangData = [];
    document.querySelectorAll('.penumpang-nama').forEach((el, idx)=>{
        penumpangData.push({
            nama_lengkap: el.value,
            nik: document.querySelectorAll('.penumpang-nik')[idx].value,
            jenis_kelamin: document.querySelectorAll('.penumpang-jk')[idx].value,
            telepon: document.querySelectorAll('.penumpang-telepon')[idx].value,
            email: document.querySelectorAll('.penumpang-email')[idx].value,
            nomor_kursi: (document.querySelectorAll('.penumpang-nomor-kursi')[idx] ? document.querySelectorAll('.penumpang-nomor-kursi')[idx].value : null)
        });
    });

    const bookingData = {
        customer_id: document.getElementById('customerSelect').value || null,
        nama_pemesan: document.getElementById('namaPemesan').value,
        telepon_pemesan: document.getElementById('teleponPemesan').value,
        email_pemesan: document.getElementById('emailPemesan').value,
        jadwal_id: document.getElementById('jadwalSelect').value,
        jumlah_penumpang: document.getElementById('jumlahPenumpang').value,
        penumpang: penumpangData,
        kode_promo: document.getElementById('kodePromo').value,
        catatan: document.getElementById('catatan') ? document.getElementById('catatan').value : null,
    };

    fetch('/admin/api/pemesanan/create', {
        method: 'POST',
        headers: {
            'Content-Type':'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(bookingData)
    })
    .then(r=>r.json())
    .then(data=>{
        if(data.success){ alert('Pemesanan berhasil dibuat. Kode: ' + data.data.kode_booking); window.location.href = '/admin/transaksi/perjalanan'; }
        else { alert('Error: ' + (data.message || 'Gagal membuat pemesanan')); }
    })
    .catch(e=>{ console.error(e); alert('Terjadi kesalahan'); });
}

// Initialize default state
document.addEventListener('DOMContentLoaded', function(){ loadPenumpangForm(); document.getElementById('promoResult').dataset.diskon='0'; document.getElementById('promoResult').dataset.valid='false'; });
</script>

@endsection
