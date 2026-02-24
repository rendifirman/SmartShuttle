<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SmartRentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmartRentETicketController extends Controller
{
    /**
     * Display e-ticket for SmartRent
     */
    public function show($orderNumber)
    {
        // Find transaction by order number and ensure it belongs to current user
        $transaction = SmartRentTransaction::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check if transaction is paid using is_paid accessor (supports: paid, settlement, success, completed)
        if (!$transaction->is_paid) {
            return redirect()->route('smartrent.riwayat')
                ->with('error', 'E-Ticket hanya tersedia untuk transaksi yang sudah dibayar dan dikonfirmasi.');
        }

        // Generate QR code if not exists
        if (!$transaction->qr_code || !$transaction->qr_path) {
            $this->generateQrCode($transaction);
            $transaction->refresh();
        }

        // Get price breakdown
        $priceBreakdown = $this->getPriceBreakdown($transaction);

        return view('customer.smartrent-e-ticket', compact('transaction', 'priceBreakdown'));
    }

    /**
     * Download e-ticket as PDF
     */
    public function download($orderNumber)
    {
        $transaction = SmartRentTransaction::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check if paid using is_paid accessor (supports: paid, settlement, success, completed)
        if (!$transaction->is_paid) {
            return redirect()->route('smartrent.riwayat')
                ->with('error', 'E-Ticket hanya tersedia untuk transaksi yang sudah dibayar.');
        }

        // For now, redirect to show page with info
        return redirect()->route('smartrent.e-ticket', $orderNumber)
            ->with('info', 'Fitur download PDF akan segera tersedia.');
    }

    /**
     * Print e-ticket
     */
    public function print($orderNumber)
    {
        $transaction = SmartRentTransaction::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check if paid using is_paid accessor (supports: paid, settlement, success, completed)
        if (!$transaction->is_paid) {
            return redirect()->route('smartrent.riwayat')
                ->with('error', 'E-Ticket hanya tersedia untuk transaksi yang sudah dibayar.');
        }

        // Get price breakdown
        $priceBreakdown = $this->getPriceBreakdown($transaction);

        return view('customer.smartrent-e-ticket-print', compact('transaction', 'priceBreakdown'));
    }

    /**
     * Get ticket data via API (for AJAX)
     */
    public function getTicketData($orderNumber)
    {
        $transaction = SmartRentTransaction::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check if paid using is_paid accessor (supports: paid, settlement, success, completed)
        if (!$transaction->is_paid) {
            return response()->json([
                'success' => false,
                'message' => 'E-Ticket hanya tersedia untuk transaksi yang sudah dibayar.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'order_number' => $transaction->order_number,
                'invoice_number' => $transaction->invoice_number,
                'customer_name' => $transaction->customer_name,
                'customer_email' => $transaction->customer_email,
                'customer_phone' => $transaction->customer_phone,
                'vehicle_name' => $transaction->vehicle_name,
                'service_type' => $transaction->service_type_label,
                'duration' => $transaction->duration_text,
                'rental_period' => $transaction->rental_period,
                'pickup_location' => $transaction->pickup_location,
                'total_price' => $transaction->formatted_total_price,
                'qr_url' => $transaction->qr_path ? asset($transaction->qr_path) : null,
                'paid_at' => $transaction->paid_at ? $transaction->paid_at->format('d M Y H:i') : '-',
                'status' => $transaction->status_label,
                'payment_status' => $transaction->payment_status_label,
            ]
        ]);
    }

    /**
     * Generate QR code for transaction
     */
    private function generateQrCode(SmartRentTransaction $transaction)
    {
        try {
            $qrData = $transaction->generateQrData();

            $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrData);
            $response = Http::get($qrApiUrl);

            if ($response->ok()) {
                $fileName = 'rent/' . $transaction->order_number . '_' . time() . '.png';
                Storage::disk('public')->put('qr/' . $fileName, $response->body());

                $transaction->qr_code = $fileName;
                $transaction->qr_path = '/storage/qr/' . $fileName;
                $transaction->save();
            } else {
                Log::error('Failed to fetch QR Code from external service for: ' . $transaction->order_number . ' Response code: ' . $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Failed to generate QR code for SmartRent: ' . $e->getMessage());
        }
    }

    /**
     * Get price breakdown
     */
    private function getPriceBreakdown(SmartRentTransaction $transaction)
    {
        $breakdown = [];

        // Vehicle rental
        $breakdown[] = [
            'label' => 'Sewa ' . $transaction->vehicle_name . ' (' . $transaction->duration . ' hari)',
            'amount' => $transaction->vehicle_total,
            'formatted' => 'Rp ' . number_format($transaction->vehicle_total, 0, ',', '.')
        ];

        // Driver fee if with driver
        if ($transaction->service_type === 'with_driver' && $transaction->driver_total > 0) {
            $breakdown[] = [
                'label' => 'Biaya Sopir (' . $transaction->duration . ' hari)',
                'amount' => $transaction->driver_total,
                'formatted' => 'Rp ' . number_format($transaction->driver_total, 0, ',', '.')
            ];
        }

        return $breakdown;
    }
}