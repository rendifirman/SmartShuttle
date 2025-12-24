<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone',
        'nik',
        'tanggal_lahir',
        'jenis_kelamin',
        'avatar',
        'member_point',
        'loyalty_point',
        'membership_level',
        'membership_status',
        'membership_start_date',
        'membership_end_date',
        'membership_fee',
        'membership_payment_method',
        'membership_payment_status',
        'membership_transaction_id',
        'two_factor_enabled',
        'status',
        'google_id',    // Field untuk Google Auth
        'provider'      // Field untuk Google Auth
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_enabled' => 'boolean',
        'member_point' => 'integer',
        'loyalty_point' => 'integer',
        'tanggal_lahir' => 'date',
        'membership_start_date' => 'date',
        'membership_end_date' => 'date',
        'membership_fee' => 'decimal:2'
    ];

    protected $attributes = [
        'status' => 'active',
        'two_factor_enabled' => false,
        'member_point' => 0,
        'loyalty_point' => 0,
        'membership_level' => 'Bronze',
        'membership_status' => 'non_member',
        'google_id' => null,
        'provider' => null
    ];

    // Relasi
    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'customer_id');
    }

    public function membershipPayments()
    {
        return $this->hasMany(MembershipPayment::class);
    }

    // Helper methods untuk Google Auth
    public function isGoogleUser()
    {
        return !empty($this->google_id) && $this->provider === 'google';
    }

    // Helper method untuk cek status user
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isInactive()
    {
        return $this->status === 'inactive';
    }

    public function isMemberActive()
    {
        return $this->membership_status === 'active' &&
               $this->membership_end_date &&
               $this->membership_end_date->isFuture();
    }

    // Method lainnya tetap sama
    public function calculateLoyaltyPointsToAdd()
    {
        // Hanya tambah loyalty point jika membership aktif
        if ($this->membership_status !== 'active') {
            return 0;
        }

        switch ($this->membership_level) {
            case 'Bronze':
                return 50;
            case 'Silver':
                return 60;
            case 'Gold':
                return 80;
            case 'Platinum':
                return 100;
            default:
                return 50;
        }
    }

    public function updateMembershipLevel()
    {
        $points = $this->member_point;

        if ($points >= 4500) {
            $this->membership_level = 'Platinum';
        } elseif ($points >= 2500) {
            $this->membership_level = 'Gold';
        } elseif ($points >= 1000) {
            $this->membership_level = 'Silver';
        } else {
            $this->membership_level = 'Bronze';
        }

        return $this->membership_level;
    }

    public function getMembershipProgressAttribute()
    {
        $ranges = [
            'Bronze' => ['min' => 0, 'max' => 1000],
            'Silver' => ['min' => 1000, 'max' => 2500],
            'Gold' => ['min' => 2500, 'max' => 4500],
            'Platinum' => ['min' => 4500, 'max' => 6000],
        ];

        $currentRange = $ranges[$this->membership_level] ?? $ranges['Bronze'];

        if ($this->member_point >= $currentRange['max']) {
            return 100;
        } elseif ($this->member_point <= $currentRange['min']) {
            return 0;
        } else {
            return (($this->member_point - $currentRange['min']) / ($currentRange['max'] - $currentRange['min'])) * 100;
        }
    }

    public function getNextMembershipLevelAttribute()
    {
        $levels = ['Bronze', 'Silver', 'Gold', 'Platinum'];
        $currentIndex = array_search($this->membership_level, $levels);

        return $currentIndex < count($levels) - 1 ? $levels[$currentIndex + 1] : 'Platinum';
    }

    public function getPointsNeededForNextLevelAttribute()
    {
        $ranges = [
            'Bronze' => ['min' => 0, 'max' => 1000],
            'Silver' => ['min' => 1000, 'max' => 2500],
            'Gold' => ['min' => 2500, 'max' => 4500],
            'Platinum' => ['min' => 4500, 'max' => 6000],
        ];

        if ($this->membership_level === 'Platinum') {
            return 0;
        }

        $nextLevel = $this->next_membership_level;
        $nextMin = $ranges[$nextLevel]['min'];

        $pointsNeeded = $nextMin - $this->member_point;
        return $pointsNeeded > 0 ? $pointsNeeded : 0;
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        return null;
    }

    // Method untuk mendapatkan inisial nama
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        $initials = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }

        // Jika hanya 1 kata, ambil 2 karakter pertama
        if (strlen($initials) == 1) {
            $initials = strtoupper(substr($this->name, 0, 2));
        } else {
            // Ambil maksimal 2 huruf inisial
            $initials = substr($initials, 0, 2);
        }

        return $initials;
    }

    public function getMembershipStatusLabelAttribute()
    {
        return match($this->membership_status) {
            'non_member' => 'Non-Member',
            'pending' => 'Menunggu Pembayaran',
            'active' => 'Aktif',
            'expired' => 'Kadaluarsa',
            default => 'Non-Member'
        };
    }

    public function getMembershipStatusColorAttribute()
    {
        return match($this->membership_status) {
            'non_member' => 'secondary',
            'pending' => 'warning',
            'active' => 'success',
            'expired' => 'danger',
            default => 'secondary'
        };
    }

    public function canUseLoyaltyDiscount()
    {
        return $this->isMemberActive() && $this->loyalty_point >= 50;
    }

    public function calculateDiscountFromLoyaltyPoints($totalAmount)
    {
        if (!$this->canUseLoyaltyDiscount()) {
            return 0;
        }

        $discountPercentage = 0;

        if ($this->loyalty_point >= 150) {
            $discountPercentage = 15;
        } elseif ($this->loyalty_point >= 100) {
            $discountPercentage = 10;
        } elseif ($this->loyalty_point >= 50) {
            $discountPercentage = 5;
        }

        $discount = ($totalAmount * $discountPercentage) / 100;

        // Max discount 50% of total amount
        $maxDiscount = $totalAmount * 0.5;

        return min($discount, $maxDiscount);
    }

    public function useLoyaltyPoints($pointsToUse)
    {
        if ($this->loyalty_point >= $pointsToUse) {
            $this->loyalty_point -= $pointsToUse;
            return $this->save();
        }
        return false;
    }

    public function addMemberPoints($points)
    {
        if ($this->isMemberActive()) {
            $this->member_point += $points;
            $this->updateMembershipLevel();
            return $this->save();
        }
        return false;
    }

    public function addLoyaltyPoints($points)
    {
        if ($this->isMemberActive()) {
            $this->loyalty_point += $points;
            return $this->save();
        }
        return false;
    }

    public function activateMembership($durationMonths = 12)
    {
        $this->membership_status = 'active';
        $this->membership_start_date = now();
        $this->membership_end_date = now()->addMonths($durationMonths);
        $this->membership_level = 'Bronze'; // Reset ke Bronze saat aktivasi baru
        $this->member_point = 0; // Reset member point
        $this->loyalty_point = 0; // Reset loyalty point
        return $this->save();
    }
}
