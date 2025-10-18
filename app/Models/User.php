<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'refferal_code',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->refferal_code)) {
                $user->refferal_code = self::generateUniqueReferralCode();
            }
        });
    }

    public static function generateUniqueReferralCode($length = 6)
    {
        do {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[random_int(0, strlen($characters) - 1)];
            }
        } while (self::where('refferal_code', $code)->exists());

        return $code;
    }

    public function getStatusBadgeColorAttribute()
    {
        return match ($this->status) {
            'active' => 'success',
            'inactive' => 'warning',
            'suspended' => 'danger',
            default => 'secondary'
        };
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function primaryWallet()
    {
        return $this->hasOne(Wallet::class)->where('is_primary', true);
    }
    public function referrals()
    {
        return $this->hasMany(ReferralUsage::class, 'user_referral');
    }
    public function usedReferral()
    {
        return $this->hasOne(ReferralUsage::class, 'used_by');
    }
}
