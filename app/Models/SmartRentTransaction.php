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
     * Payment statuses that are considered "paid"
     */
    const PAID_STATUSES = ['paid', 'settlement', 'success', 'completed', 'lunas'];
    
    /**
     * Payment statuses that are considered "pending"
     */
    const PENDING_STATUSES = ['pending', 'waiting', 'unpaid', 'menunggu', 'process'];
    
    /**
     * Payment statuses that are considered "failed/cancelled"
     */
    const FAILED_STATUSES = ['expired', 'failed', 'cancelled', 'batal', 'rejected'];

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
        $paymentStatus = strtolower($this->payment_status);
        
        $labels = [
            'unpaid' => 'Belum Dibayar',
            'pending' => 'Menunggu Pembayaran',
            'waiting' => 'Menunggu Pembayaran',
            'paid' => 'Lunas',
            'settlement' => 'Lunas',
            'success' => 'Lunas',
            'completed' => 'Lunas',
            'lunas' => 'Lunas',
            'failed' => 'Gagal',
            'expired' => 'Kadaluarsa',
            'cancelled' => 'Dibatalkan',
            'batal' => 'Dibatalkan',
        ];

        return $labels[$paymentStatus] ?? ucfirst($this->payment_status);
    }

    /**
     * Get filter status for riwayat page (lunas/menunggu/batal)
     */
    public function getFilterStatusAttribute()
    {
        $paymentStatus = strtolower($this->payment_status);
        
        if (in_array($paymentStatus, self::PAID_STATUSES)) {
            return 'lunas';
        } elseif (in_array($paymentStatus, self::FAILED_STATUSES)) {
            return 'batal';
        } else {
            return 'menunggu';
        }
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
     * Check if transaction is paid (supports multiple payment status values)
     */
    public function getIsPaidAttribute()
    {
        $paymentStatus = strtolower($this->payment_status);
        return in_array($paymentStatus, self::PAID_STATUSES);
    }

    /**
     * Check if transaction is pending
     */
    public function getIsPendingAttribute()
    {
        $paymentStatus = strtolower($this->payment_status);
        return in_array($paymentStatus, self::PENDING_STATUSES);
    }

    /**
     * Check if transaction is failed/cancelled
     */
    public function getIsFailedAttribute()
    {
        $paymentStatus = strtolower($this->payment_status);
        return in_array($paymentStatus, self::FAILED_STATUSES);
    }

    /**
     * Check if e-ticket can be shown (paid and status allows)
     */
    public function canShowETicket()
    {
        return $this->is_paid && in_array($this->status, ['confirmed', 'ongoing', 'completed', 'pending_payment']);
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
     * Scope: Filter by filter status (lunas/menunggu/batal)
     */
    public function scopeByFilterStatus($query, $filterStatus)
    {
        switch ($filterStatus) {
            case 'lunas':
                return $query->whereIn('payment_status', self::PAID_STATUSES);
            case 'batal':
                return $query->whereIn('payment_status', self::FAILED_STATUSES);
            case 'menunggu':
                return $query->whereNotIn('payment_status', array_merge(self::PAID_STATUSES, self::FAILED_STATUSES));
            default:
                return $query;
        }
    }

    /**
     * Scope: Latest first
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}