<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;

class CekReservasiController extends Controller
{
    /**
     * Proses form cek reservasi
     */
    public function proses(Request $request)
    {
        $request->validate([
            'kode' => 'required|string'
        ]);

        $kode = trim($request->kode);
        $pemesanan = Pemesanan::with(['jadwal.shuttle', 'detailPenumpang', 'jadwal.rutes'])
            ->where('kode_booking', $kode)
            ->first();

        if ($pemesanan) {
            // Prepare data similar to ETicketController but without ownership check
            $jadwal = $pemesanan->jadwal;

            $from = 'Jakarta';
            $to = 'Jatinangor';
            if ($jadwal && $jadwal->rutes && $jadwal->rutes->count() > 0) {
                $firstRoute = $jadwal->rutes->first();
                $lastRoute = $jadwal->rutes->last();
                $from = $firstRoute->kota_asal ?? $firstRoute->asal ?? $from;
                $to = $lastRoute->kota_tujuan ?? $lastRoute->tujuan ?? $to;
            }

            $waktuBerangkat = $jadwal ? \Carbon\Carbon::parse($jadwal->waktu_keberangkatan) : null;
            $waktuSampai = $waktuBerangkat ? $waktuBerangkat->copy()->addHours(3)->addMinutes(30) : null;

            $penumpangData = $pemesanan->detailPenumpang ?? [];

            $data = [
                'pemesanan' => $pemesanan,
                'jadwal' => $jadwal,
                'from' => $from,
                'to' => $to,
                'date' => $jadwal ? \Carbon\Carbon::parse($jadwal->tanggal_keberangkatan)->isoFormat('dddd, D MMMM YYYY') : null,
                'time' => $waktuBerangkat ? $waktuBerangkat->format('H:i') : null,
                'estimasi_sampai' => $waktuSampai ? $waktuSampai->format('H:i') : null,
                'customer_name' => $pemesanan->nama_pemesan,
                'customer_phone' => $pemesanan->telepon_pemesan,
                'customer_email' => $pemesanan->email_pemesan,
                'penumpang' => $penumpangData,
                'shuttle' => $jadwal->shuttle ?? null,
                'nomor_kursi' => $penumpangData && count($penumpangData) ? $penumpangData->first()->nomor_kursi ?? '01' : '01',
                'kode_booking' => $pemesanan->kode_booking,
                'total_bayar' => $pemesanan->harga_total ?? 0,
            ];

            // Return the cek-reservasi view with e-ticket data included
            return view('customer.cek-reservasi', $data);
        }

        return redirect()->back()->with('error', 'Reservasi dengan kode tersebut tidak ditemukan.');
    }

    /**
     * Tampilan hasil (opsional) - redirect ke e-ticket jika ditemukan
     */
    public function hasil($kode)
    {
        $pemesanan = Pemesanan::where('kode_booking', $kode)->first();
        if ($pemesanan) {
            return redirect()->route('customer.e_ticket', ['kode_booking' => $pemesanan->kode_booking]);
        }

        return redirect()->route('customer.cek-reservasi')->with('error', 'Reservasi tidak ditemukan');
    }
}
