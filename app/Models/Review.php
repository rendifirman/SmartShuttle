<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rating',
        'review',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime:d M Y'
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk mengambil hanya yang approved
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Format tanggal
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y');
    }
}