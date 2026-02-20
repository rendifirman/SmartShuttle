<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverDetail extends Model
{
    use HasFactory;

    protected $table = 'driver_details';

    protected $fillable = [
        'user_id',
        'phone',
        'nik',
        'join_date',
        'driver_id',
        'sim_number',
        'sim_expiry_date',
        'ktp_file',
        'sim_file',
        'status'
    ];

    protected $casts = [
        'join_date' => 'date',
        'sim_expiry_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that owns the driver detail.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate driver ID if not exists
     */
    public static function generateDriverId()
    {
        $lastDriver = self::whereNotNull('driver_id')
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastDriver && $lastDriver->driver_id) {
            // Extract the last number from driver_id format DRV-YYYY-NNN
            $parts = explode('-', $lastDriver->driver_id);
            $lastNumber = isset($parts[2]) ? intval($parts[2]) : 0;
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return 'DRV-' . date('Y') . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}