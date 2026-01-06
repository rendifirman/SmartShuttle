<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profile Perusahaan - Admin Pusat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
            font-family: Inter, Arial, Helvetica, sans-serif;
        }

        body {
            margin: 0;
            background: #f8f8f6;
            color: #1f2937;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 260px;
            background: #0f2a44;
            color: #ffffff;
            padding: 22px 0;
        }

        .sidebar h2 {
            text-align: center;
            color: #ff6a21;
            font-size: 14px;
            margin-bottom: 28px;
            line-height: 1.4;
            font-weight: 700;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar li {
            padding: 10px 28px;
            font-size: 13px;
            cursor: pointer;
            color: #dbe3ea;
        }

        .sidebar li.active {
            background: #ff6a21;
            color: #ffffff;
            border-radius: 6px;
            margin: 0 14px;
        }

        .sidebar .section {
            margin-top: 18px;
            font-weight: 600;
            font-size: 12px;
            opacity: 0.7;
        }

        /* ================= MAIN ================= */
        .main {
            flex: 1;
            padding: 24px 36px 40px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 14px;
            margin-bottom: 24px;
            border-bottom: 1px solid #e5e7eb;
        }

        .topbar h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }

        .user {
            background: #eeeeee;
            padding: 6px 14px;
            border-radius: 18px;
            font-size: 13px;
        }

        /* ================= CARD ================= */
        .card {
            background: #ffffff;
            border-radius: 10px;
            padding: 22px 24px 26px;
            margin-bottom: 26px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        }

        .card h4 {
            margin: 0 0 20px;
            font-size: 15px;
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 2px solid #ff6a21;
        }

        /* ================= FORM ================= */
        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 16px;
        }

        .form-row.three {
            grid-template-columns: repeat(3, 1fr);
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 12px;
            margin-bottom: 6px;
            color: #374151;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 13px;
            background: #ffffff;
        }

        textarea {
            resize: none;
        }

        /* ================= LAYANAN ================= */
        .services {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 22px;
        }

        .service {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
            background: #fafafa;
        }

        .upload {
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            padding: 18px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 12px;
        }

        .service h5 {
            margin: 10px 0 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .list {
            font-size: 13px;
            line-height: 1.8;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <aside class="sidebar">
            <h2>
                SMART SHUTTLE<br>
                ADMIN PUSAT
            </h2>

            <ul>
                <li class="active">Dashboard</li>

                <li class="section">Master Data</li>
                <li>Profile Perusahaan</li>
                <li>Pusat</li>
                <li>Cabang</li>
                <li>Armada</li>
                <li>Driver</li>
                <li>Pegawai</li>
                <li>Rute</li>

                <li class="section">Transaksi</li>
                <li>Tiket</li>
                <li>Perjalanan</li>
                <li>Armada</li>

                <li class="section">SmartSend</li>
                <li>Tiket</li>
                <li>Perjalanan</li>
                <li>Armada</li>

                <li class="section">SmartRent</li>
                <li class="section">Laporan</li>

                <li class="section">Setting/Menu</li>
                <li>User</li>
                <li>Menu</li>
            </ul>
        </aside>

        <main class="main">
            <div class="topbar">
                <h3>Profile Perusahaan</h3>
                <div class="user">Admin Pusat</div>
            </div>

            <div class="card">
                <h4>Informasi Perusahaan</h4>

                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Perusahaan</label>
                        <input value="PT. Smart Shuttle Indonesia">
                    </div>
                    <div class="form-group">
                        <label>Nama Dagang</label>
                        <input value="Smart Shuttle Group">
                    </div>
                </div>

                <div class="form-group">
                    <label>Deskripsi Singkat</label>
                    <textarea rows="3">Smart Shuttle adalah solusi transportasi cerdas yang menghubungkan berbagai kota dan mempermudah mobilitas masyarakat dengan layanan yang cepat dan terpercaya.</textarea>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea rows="2">Jl. Sudirman No. 45, Jakarta Selatan</textarea>
                </div>

                <div class="form-row three">
                    <div class="form-group">
                        <label>Telepon</label>
                        <input value="(021) 555-1234">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input value="info@smartshuttle.co.id">
                    </div>
                    <div class="form-group">
                        <label>Website</label>
                        <input value="www.smartshuttle.co.id">
                    </div>
                </div>
            </div>

            <div class="card">
                <h4>Layanan & Unit Bisnis</h4>

                <div class="services">
                    <div class="service">
                        <div class="upload">Upload Logo Layanan</div>
                        <h5>Nama Layanan</h5>
                        <input value="SmartShuttle">
                        <h5>Deskripsi Layanan</h5>
                        <textarea rows="3">Layanan transportasi cerdas untuk perjalanan antar kota dengan armada modern dan pengemudi profesional.</textarea>
                    </div>
                    <div class="service">
                        <div class="upload">Upload Logo Layanan</div>
                        <h5>Nama Layanan</h5>
                        <input value="SmartShuttle">
                        <h5>Deskripsi Layanan</h5>
                        <textarea rows="3">Layanan transportasi cerdas untuk perjalanan antar kota dengan armada modern dan pengemudi profesional.</textarea>
                    </div>
                    <div class="service">
                        <div class="upload">Upload Logo Layanan</div>
                        <h5>Nama Layanan</h5>
                        <input value="SmartShuttle">
                        <h5>Deskripsi Layanan</h5>
                        <textarea rows="3">Layanan transportasi cerdas untuk perjalanan antar kota dengan armada modern dan pengemudi profesional.</textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Visi Perusahaan</label>
                        <textarea rows="4">Menjadi perusahaan terdepan di Indonesia dalam menyediakan solusi mobilitas dan logistik yang inovatif dan berkelanjutan demi kemudahan masyarakat.</textarea>
                    </div>
                    <div class="form-group">
                        <label>Misi Perusahaan</label>
                        <div class="list">
                            • Menyediakan layanan transportasi dan logistik yang cepat, aman, dan ramah lingkungan.<br>
                            • Mengoptimalkan penggunaan teknologi untuk meningkatkan efisiensi dan kepuasan pelanggan.<br>
                            • Membangun jaringan luas untuk mendukung mobilitas masyarakat di seluruh Indonesia.<br>
                            • Mengedepankan keselamatan dan kenyamanan dalam setiap layanan.
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <h4>Legal & Administratif</h4>

                <div class="form-row">
                    <div class="form-group">
                        <label>NPWP</label>
                        <input value="01.234.567.8-901.000">
                    </div>
                    <div class="form-group">
                        <label>Kode Izin Penyelenggaraan</label>
                        <input value="KIP-56789-XYZ">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>SIUP</label>
                        <input value="SIUP-2024-12345">
                    </div>
                    <div class="form-group">
                        <label>NIB</label>
                        <input value="1234567890123">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Nomor Sertifikat Transportasi</label>
                        <input value="TRNS-00012345">
                    </div>
                    <div class="form-group">
                        <label>TDP</label>
                        <input value="TDP-2024-98765">
                    </div>
                </div>
            </div>

            <div class="card">
                <h4>Informasi Pembentukan Perusahaan</h4>

                <div class="form-row three">
                    <div class="form-group">
                        <label>Tanggal Berdiri</label>
                        <input value="10 November 2025">
                    </div>
                    <div class="form-group">
                        <label>Penanggung Jawab Utama</label>
                        <input value="Dr. Rina Dewi">
                    </div>
                    <div class="form-group">
                        <label>Nama Pendiri</label>
                        <input value="Ir. Agus Santoso">
                    </div>
                </div>

                <div class="form-group">
                    <label>Struktur Organisasi (Text)</label>
                    <textarea rows="3">Dokumen struktur organisasi tersedia pada bagian administrasi perusahaan.</textarea>
                </div>
            </div>

            <div class="card">
                <h4>Link Halaman Kebijakan</h4>

                <div class="form-row">
                    <div class="form-group">
                        <label>Link Refund</label>
                        <input value="https://smartshuttle.co.id/refund-policy">
                    </div>
                    <div class="form-group">
                        <label>Link Privasi</label>
                        <input value="https://smartshuttle.co.id/privacy-policy">
                    </div>
                </div>

                <div class="form-group">
                    <label>Link Syarat & Ketentuan</label>
                    <input value="https://smartshuttle.co.id/terms">
                </div>
            </div>
        </main>
    </div>
</body>
</html>
