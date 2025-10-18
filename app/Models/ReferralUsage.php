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

    public function referrer()
    {
        return $this->belongsTo(User::class, 'user_referral');
    }

    public function referee()
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    public function scopeByReferrer($query, $userId)
    {
        return $query->where('user_referral', $userId);
    }

    public function scopeByReferee($query, $userId)
    {
        return $query->where('used_by', $userId);
    }
}
