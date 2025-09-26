<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_type',
        'bank_name',
        'bank_account',
        'account_name',
        'ewallet_provider',
        'ewallet_number',
        'ewallet_name',
        'is_primary',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Constants untuk wallet types
    const TYPE_BANK = 'bank';
    const TYPE_EWALLET = 'ewallet';

    const WALLET_TYPES = [
        self::TYPE_BANK => 'Bank Account',
        self::TYPE_EWALLET => 'E-Wallet',
    ];

    // Bank providers
    const BANK_PROVIDERS = [
        'BCA' => 'Bank Central Asia',
        'BNI' => 'Bank Negara Indonesia',
        'BRI' => 'Bank Rakyat Indonesia',
        'Mandiri' => 'Bank Mandiri',
        'CIMB' => 'CIMB Niaga',
        'Danamon' => 'Bank Danamon',
        'Permata' => 'Bank Permata',
        'BTN' => 'Bank Tabungan Negara',
        'BSI' => 'Bank Syariah Indonesia',
        // Bank Digital
        'SeaBank' => 'SeaBank Indonesia',
        'Jago' => 'Bank Jago',
        'BNC' => 'Bank Neo Commerce',
        'Hibank' => 'Hibank',
        'Blu' => 'BCA Digital (blu)',
        'AlloBank' => 'Allo Bank',
        'BankRaya' => 'Bank Raya Indonesia',
    ];

    const EWALLET_PROVIDERS = [
        'GoPay' => 'GoPay',
        'OVO' => 'OVO',
        'DANA' => 'DANA',
        'ShopeePay' => 'ShopeePay',
        'LinkAja' => 'LinkAja',
        'Sakuku' => 'Sakuku',
        'Jenius' => 'Jenius Pay',
        'DOKU' => 'DOKU',
        'iSaku' => 'i.Saku',
        'OctoMobile' => 'Octo Mobile',
        'Bayarind' => 'Bayarind',
        'OVONabung' => 'OVO Nabung (Rek-Wallet)',
    ];


    /**
     * Relationship dengan User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
