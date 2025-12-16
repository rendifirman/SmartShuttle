<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\User;
use App\Models\Jadwal;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\Log;

class ETicketController extends Controller
{
    /**
     * Tampilkan e-ticket lengkap
     */
    public function show($kode_booking)
    {
        // Cek jika user sudah login
        if (!session()->has('user')) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = session()->get('user');

        try {
            // Ambil data pemesanan dengan relasi yang benar
            $pemesanan = Pemesanan::with([
                'jadwal.shuttle',
                'detailPenumpang',
                'jadwal.rutes'
            ])->where('kode_booking', $kode_booking)
              ->first();

            if (!$pemesanan) {
                return redirect()->route('customer.riwayat')
                    ->with('error', 'Pemesanan dengan kode booking ' . $kode_booking . ' tidak ditemukan.');
            }

            // Cek kepemilikan
            if ($pemesanan->customer_id != $user['id']) {
                return redirect()->route('customer.riwayat')
                    ->with('error', 'Anda tidak memiliki akses ke pemesanan ini.');
            }

            // Lanjutkan dengan pemrosesan data
            $jadwal = $pemesanan->jadwal;

            if (!$jadwal) {
                return redirect()->route('customer.riwayat')
                    ->with('error', 'Data jadwal tidak ditemukan.');
            }

            // Format data untuk e-ticket
            $from = 'Jakarta';
            $to = 'Jatinangor';

            // Ambil rute dari jadwal
            if ($jadwal->rutes && $jadwal->rutes->count() > 0) {
                $firstRoute = $jadwal->rutes->first();
                $lastRoute = $jadwal->rutes->last();

                $from = $firstRoute->kota_asal ?? $firstRoute->asal ?? 'Jakarta';
                $to = $lastRoute->kota_tujuan ?? $lastRoute->tujuan ?? 'Jatinangor';
            }

            // Hitung estimasi waktu
            $waktuBerangkat = Carbon::parse($jadwal->waktu_keberangkatan);
            $waktuSampai = $waktuBerangkat->copy()->addHours(3)->addMinutes(30);

            // Ambil nomor kursi dari detail penumpang
            $nomor_kursi = '01';
            $penumpangData = [];

            if ($pemesanan->detailPenumpang && $pemesanan->detailPenumpang->count() > 0) {
                $firstPenumpang = $pemesanan->detailPenumpang->first();
                $nomor_kursi = $firstPenumpang->nomor_kursi ?? '01';
                $penumpangData = $pemesanan->detailPenumpang;
            }

            // Get shuttle info
            $shuttle = $jadwal->shuttle ?? null;

            // Get user data
            $userData = User::find($user['id']);

            $data = [
                'pemesanan' => $pemesanan,
                'jadwal' => $jadwal,
                'from' => $from,
                'to' => $to,
                'date' => Carbon::parse($jadwal->tanggal_keberangkatan)->isoFormat('dddd, D MMMM YYYY'),
                'time' => $waktuBerangkat->format('H:i'),
                'estimasi_sampai' => $waktuSampai->format('H:i'),
                'customer_name' => $pemesanan->nama_pemesan ?? $userData->name,
                'customer_phone' => $pemesanan->telepon_pemesan ?? ($userData->phone ?? '-'),
                'customer_email' => $pemesanan->email_pemesan ?? $userData->email,
                'penumpang' => $penumpangData,
                'shuttle' => $shuttle,
                'nomor_kursi' => $nomor_kursi,
                'kode_booking' => $pemesanan->kode_booking,
                'user' => $userData,
                'total_bayar' => $pemesanan->harga_total ?? 0,
            ];

            return view('customer.e_ticket', $data);

        } catch (\Exception $e) {
            Log::error('ETicketController error: ' . $e->getMessage());
            return redirect()->route('customer.riwayat')
                ->with('error', 'Tidak dapat memuat e-ticket: ' . $e->getMessage());
        }
    }

    /**
     * Download e-ticket sebagai PDF
     */
    public function download($kode_booking)
    {
        if (!session()->has('user')) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        try {
            $user = session()->get('user');
            $pemesanan = Pemesanan::with(['jadwal.shuttle', 'detailPenumpang', 'jadwal.rutes'])
                ->where('kode_booking', $kode_booking)
                ->where('customer_id', $user['id'])
                ->firstOrFail();

            // Format data untuk PDF
            $jadwal = $pemesanan->jadwal;
            $waktuBerangkat = Carbon::parse($jadwal->waktu_keberangkatan);
            $waktuSampai = $waktuBerangkat->copy()->addHours(3)->addMinutes(30);

            $from = 'Jakarta';
            $to = 'Jatinangor';

            if ($jadwal->rutes && $jadwal->rutes->count() > 0) {
                $firstRoute = $jadwal->rutes->first();
                $lastRoute = $jadwal->rutes->last();
                $from = $firstRoute->kota_asal ?? $firstRoute->asal ?? 'Jakarta';
                $to = $lastRoute->kota_tujuan ?? $lastRoute->tujuan ?? 'Jatinangor';
            }

            $userData = User::find($user['id']);

            $data = [
                'pemesanan' => $pemesanan,
                'from' => $from,
                'to' => $to,
                'date' => Carbon::parse($jadwal->tanggal_keberangkatan)->isoFormat('dddd, D MMMM YYYY'),
                'time' => $waktuBerangkat->format('H:i'),
                'estimasi_sampai' => $waktuSampai->format('H:i'),
                'customer_name' => $pemesanan->nama_pemesan ?? $userData->name,
                'customer_phone' => $pemesanan->telepon_pemesan ?? ($userData->phone ?? '-'),
                'customer_email' => $pemesanan->email_pemesan ?? $userData->email,
                'penumpang' => $pemesanan->detailPenumpang ?? [],
                'nomor_kursi' => $pemesanan->detailPenumpang->first()->nomor_kursi ?? '01',
                'kode_booking' => $pemesanan->kode_booking,
                'plat_nomor' => $jadwal->shuttle->plat_nomor ?? 'B 1234 CD',
                'user' => $userData,
                'total_bayar' => $pemesanan->harga_total ?? 0,
            ];

            $pdf = PDF::loadView('customer.e_ticket', $data);

            return $pdf->download('e-ticket-' . $kode_booking . '.pdf');

        } catch (\Exception $e) {
            Log::error('ETicketController download error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengunduh e-ticket.');
        }
    }
}
