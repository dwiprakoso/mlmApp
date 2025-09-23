<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_referral',
        'used_by',
    ];

    /**
     * Relationship ke User yang punya referral code
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'user_referral');
    }

    /**
     * Relationship ke User yang menggunakan referral code
     */
    public function referee()
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    /**
     * Scope untuk filter berdasarkan user yang punya referral
     */
    public function scopeByReferrer($query, $userId)
    {
        return $query->where('user_referral', $userId);
    }

    /**
     * Scope untuk filter berdasarkan user yang pakai referral
     */
    public function scopeByReferee($query, $userId)
    {
        return $query->where('used_by', $userId);
    }
}
