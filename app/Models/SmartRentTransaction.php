<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmartRentTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'smartrent_transactions';

    protected $fillable = [
        'order_number',
        'invoice_number',
        'user_id',
        'vehicle_id',
        'vehicle_name',
        'vehicle_type',
        'vehicle_price',
        'duration',
        'vehicle_total',
        'service_type',
        'driver_price_per_day',
        'driver_total',
        'total_price',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'pickup_location',
        'notes',
        'ktp_path',
        'sim_path',
        'other_document_path',
        'payment_status',
        'payment_method',
        'paid_at',
        'payment_proof_path',
        'status',
        'qr_code',
        'qr_path',
        'additional_data',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'vehicle_price' => 'decimal:2',
        'driver_price_per_day' => 'decimal:2',
        'vehicle_total' => 'decimal:2',
        'driver_total' => 'decimal:2',
        'total_price' => 'decimal:2',
        'paid_at' => 'datetime',
        'additional_data' => 'array',
    ];

    /**
     * Relation to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get displayable status label
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending_payment' => 'Menunggu Pembayaran',
            'confirmed' => 'Dikonfirmasi',
            'ongoing' => 'Sedang Berlangsung',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Get displayable payment status label
     */
    public function getPaymentStatusLabelAttribute()
    {
        $labels = [
            'unpaid' => 'Belum Dibayar',
            'pending' => 'Proses',
            'paid' => 'Terbayar',
            'failed' => 'Gagal',
            'cancelled' => 'Dibatalkan',
        ];

        return $labels[$this->payment_status] ?? $this->payment_status;
    }

    /**
     * Format total price as currency
     */
    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    /**
     * Format vehicle price as currency
     */
    public function getFormattedVehiclePriceAttribute()
    {
        return 'Rp ' . number_format($this->vehicle_price, 0, ',', '.');
    }

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber()
    {
        $prefix = 'SR';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -6));
        
        return $prefix . $date . $random;
    }

    /**
     * Generate invoice number
     */
    public static function generateInvoiceNumber()
    {
        $prefix = 'INV/SR';
        $date = now()->format('Ymd');
        $sequence = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return $prefix . '/' . $date . '/' . $sequence;
    }

    /**
     * Get service type label
     */
    public function getServiceTypeLabelAttribute()
    {
        return $this->service_type === 'with_driver' ? 'Dengan Sopir' : 'Sewa Mandiri';
    }

    /**
     * Get formatted duration text
     */
    public function getDurationTextAttribute()
    {
        if (!$this->duration) {
            return '-';
        }

        return $this->duration . ' Hari';
    }

    /**
     * Get formatted rental period
     */
    public function getRentalPeriodAttribute()
    {
        if (!$this->start_date || !$this->end_date) {
            return '-';
        }

        $start = $this->start_date->format('d M Y');
        $end = $this->end_date->format('d M Y');
        
        return $start . ' - ' . $end;
    }

    /**
     * Generate QR Code data for E-Ticket
     */
    public function generateQrData()
    {
        $data = [
            'type' => 'smartrent',
            'order' => $this->order_number,
            'invoice' => $this->invoice_number,
            'customer' => $this->customer_name,
            'vehicle' => $this->vehicle_name,
            'service' => $this->service_type,
            'start' => $this->start_date ? $this->start_date->format('Y-m-d') : null,
            'end' => $this->end_date ? $this->end_date->format('Y-m-d') : null
        ];
        
        return 'SMARTRENT:' . json_encode($data);
    }

    /**
     * Scope: Filter by user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filter by payment status
     */
    public function scopeByPaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Latest first
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Check if transaction can show e-ticket
     */
    public function canShowETicket()
    {
        return $this->payment_status === 'paid' && in_array($this->status, ['confirmed', 'ongoing', 'completed']);
    }
}