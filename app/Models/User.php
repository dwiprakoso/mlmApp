<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
}
