<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // Cek jika user sudah login menggunakan Auth
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();

        try {
            // Ambil data pemesanan dengan relasi yang benar
            $pemesanan = Pemesanan::with([
                'jadwal.shuttle',
                'driverJadwal.driver',
                'detailPenumpang',
                'jadwal.rutes',
                'user'
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

            // Determine if this is a driver_jadwal booking or legacy jadwal booking
            if ($pemesanan->id_jadwal_driver && $pemesanan->driverJadwal) {
                // NEW FLOW: From driver_jadwals
                $driverJadwal = $pemesanan->driverJadwal;
                $detailRute = $driverJadwal->getDetailRute();
                $from = $detailRute['kota_asal'] ?? 'Kota Asal';
                $to = $detailRute['kota_tujuan'] ?? 'Kota Tujuan';
                $date = Carbon::parse($driverJadwal->tanggal)->isoFormat('dddd, D MMMM YYYY');
                $waktuBerangkat = Carbon::parse($driverJadwal->waktu_keberangkatan);
                $shuttle = null; // Driver jadwals don't directly link to shuttle, but we can try to get from driver

                // Safely calculate arrival time
                $waktuSampai = $waktuBerangkat->copy();
                if ($driverJadwal->waktu_kedatangan) {
                    $waktuSampai = Carbon::parse($driverJadwal->waktu_kedatangan);
                } else {
                    $waktuSampai->addHours(3)->addMinutes(30);
                }
            } else {
                // LEGACY FLOW: From jadwals
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

                $date = Carbon::parse($jadwal->tanggal_keberangkatan)->isoFormat('dddd, D MMMM YYYY');
                $waktuBerangkat = Carbon::parse($jadwal->waktu_keberangkatan);
                $waktuSampai = $waktuBerangkat->copy()->addHours(3)->addMinutes(30);
                $shuttle = $jadwal->shuttle ?? null;
            }

            // Ambil nomor kursi dari detail penumpang
            $nomor_kursi = '01';
            $penumpangData = [];

            if ($pemesanan->detailPenumpang && $pemesanan->detailPenumpang->count() > 0) {
                $firstPenumpang = $pemesanan->detailPenumpang->first();
                $nomor_kursi = $firstPenumpang->nomor_kursi ?? '01';
                $penumpangData = $pemesanan->detailPenumpang;
            }

            // Get shuttle info
            $shuttle = $shuttle ?? null;

            // Get user data
            $userData = $user;

            $data = [
                'pemesanan' => $pemesanan,
                'jadwal' => $pemesanan->jadwal,
                'driverJadwal' => $pemesanan->driverJadwal,
                'from' => $from,
                'to' => $to,
                'date' => $date,
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
        // make sure customer is authenticated using the same guard as the show() method
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();

        try {
            // if the code contains passenger suffix (-PXX), strip it for lookup
            $lookupCode = $kode_booking;
            if (strpos($kode_booking, '-P') !== false) {
                $lookupCode = substr($kode_booking, 0, strpos($kode_booking, '-P'));
            }

            // fetch reservation with all relations (including driverJadwal for new flow)
            $pemesanan = Pemesanan::with([
                'jadwal.shuttle',
                'driverJadwal.driver',
                'detailPenumpang',
                'jadwal.rutes',
                'user'
            ])
                ->where('kode_booking', $lookupCode)
                ->where('customer_id', $user->id)
                ->firstOrFail();

            // check ownership just in case
            if ($pemesanan->customer_id != $user->id) {
                return redirect()->route('customer.riwayat')
                    ->with('error', 'Anda tidak memiliki akses ke pemesanan ini.');
            }

            // compute the same variables as in show()
            if ($pemesanan->id_jadwal_driver && $pemesanan->driverJadwal) {
                // new driver_jadwals flow
                $driverJadwal = $pemesanan->driverJadwal;
                $detailRute = $driverJadwal->getDetailRute();
                $from = $detailRute['kota_asal'] ?? 'Kota Asal';
                $to = $detailRute['kota_tujuan'] ?? 'Kota Tujuan';
                $date = Carbon::parse($driverJadwal->tanggal)->isoFormat('dddd, D MMMM YYYY');
                $waktuBerangkat = Carbon::parse($driverJadwal->waktu_keberangkatan);
                $shuttle = null;

                $waktuSampai = $waktuBerangkat->copy();
                if ($driverJadwal->waktu_kedatangan) {
                    $waktuSampai = Carbon::parse($driverJadwal->waktu_kedatangan);
                } else {
                    $waktuSampai->addHours(3)->addMinutes(30);
                }
            } else {
                // legacy jadwals flow
                $jadwal = $pemesanan->jadwal;
                if (!$jadwal) {
                    return redirect()->route('customer.riwayat')
                        ->with('error', 'Data jadwal tidak ditemukan.');
                }

                $from = 'Jakarta';
                $to = 'Jatinangor';
                if ($jadwal->rutes && $jadwal->rutes->count() > 0) {
                    $firstRoute = $jadwal->rutes->first();
                    $lastRoute = $jadwal->rutes->last();

                    $from = $firstRoute->kota_asal ?? $firstRoute->asal ?? 'Jakarta';
                    $to = $lastRoute->kota_tujuan ?? $lastRoute->tujuan ?? 'Jatinangor';
                }

                $date = Carbon::parse($jadwal->tanggal_keberangkatan)->isoFormat('dddd, D MMMM YYYY');
                $waktuBerangkat = Carbon::parse($jadwal->waktu_keberangkatan);
                $waktuSampai = $waktuBerangkat->copy()->addHours(3)->addMinutes(30);
                $shuttle = $jadwal->shuttle ?? null;
            }

            $nomor_kursi = '01';
            if ($pemesanan->detailPenumpang && $pemesanan->detailPenumpang->count() > 0) {
                $nomor_kursi = $pemesanan->detailPenumpang->first()->nomor_kursi ?? '01';
            }

            $userData = $user;

            // prepare dataset for view
            $data = [
                'pemesanan' => $pemesanan,
                'jadwal' => $pemesanan->jadwal,
                'driverJadwal' => $pemesanan->driverJadwal,
                'from' => $from,
                'to' => $to,
                'date' => $date,
                'time' => $waktuBerangkat->format('H:i'),
                'estimasi_sampai' => $waktuSampai->format('H:i'),
                'customer_name' => $pemesanan->nama_pemesan ?? $userData->name,
                'customer_phone' => $pemesanan->telepon_pemesan ?? ($userData->phone ?? '-'),
                'customer_email' => $pemesanan->email_pemesan ?? $userData->email,
                'penumpang' => $pemesanan->detailPenumpang,
                'shuttle' => $shuttle,
                'nomor_kursi' => $nomor_kursi,
                'kode_booking' => $pemesanan->kode_booking,
                'user' => $userData,
                'total_bayar' => $pemesanan->harga_total ?? 0,
            ];

            // generate base64 QR data for each ticket (needed because Dompdf cannot fetch external HTTPS images reliably)
            $qr_map = [];
            if ($pemesanan->detailPenumpang && $pemesanan->detailPenumpang->count() > 0) {
                foreach ($pemesanan->detailPenumpang as $idx => $dp) {
                    $ticketCode = $pemesanan->kode_booking . '-P' . str_pad($idx+1, 2, '0', STR_PAD_LEFT);
                    $qr_map[$ticketCode] = $this->fetchQrBase64('SMARTSHUTTLE:' . $ticketCode . ':CHECKIN:' . ($pemesanan->id ?? ''));
                }
            } else {
                $ticketCode = $pemesanan->kode_booking;
                $qr_map[$ticketCode] = $this->fetchQrBase64('SMARTSHUTTLE:' . $ticketCode . ':CHECKIN:' . ($pemesanan->id ?? ''));
            }
            $data['qr_map'] = $qr_map;

            // generate PDF from the dedicated PDF template so it stays consistent
            // with manual downloads (view name duplicated but kept separate for clarity)
            $pdf = PDF::loadView('customer.e_ticket_pdf', $data);
            return $pdf->download('e-ticket-' . $kode_booking . '.pdf');
        } catch (\Exception $e) {
            Log::error('ETicketController download error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengunduh e-ticket.');
        }
    }

    /**
     * Fetches a QR code image from the external service and returns a data URI.
     * If the request fails, returns an empty string so the view can fallback.
     */
    private function fetchQrBase64(string $text): string
    {
        $url = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=10&data=' . urlencode($text);
        try {
            $image = @file_get_contents($url);
            if ($image !== false) {
                return 'data:image/png;base64,' . base64_encode($image);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to fetch QR code: ' . $e->getMessage());
        }
        return '';
    }
}
