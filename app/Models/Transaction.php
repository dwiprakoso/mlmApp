<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'reference',
        'amount',
        'type',
        'source_balance_type',
        'wallet_id',
        'withdrawal_fee',
        'source_user_id',
        'related_transaction_id',
        'status',
        'payment_method',
        'payment_proof',
        'approved_by',
    ];
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function relatedTransaction()
    {
        return $this->belongsTo(Transaction::class, 'related_transaction_id');
    }

    public function sourceUser()
    {
        return $this->belongsTo(User::class, 'source_user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Balance Calculation (New System with Legacy Support)
    |--------------------------------------------------------------------------
    */

    /**
     * Get balance by specific type (deposit/revenue/commission)
     * Formula: income - withdrawals from that source - legacy portion
     * 
     * @param int $userId
     * @param string $type (deposit|revenue|commission)
     * @return float
     */
    public static function getBalanceByType($userId, $type)
    {
        // Income untuk type ini
        $income = self::where('user_id', $userId)
            ->where('status', 'success')
            ->where('type', $type)
            ->sum('amount');

        // Withdrawal DARI source ini (sistem baru)
        $withdrawal = self::where('user_id', $userId)
            ->whereIn('status', ['success', 'pending'])
            ->where('type', 'withdraw')
            ->where('source_balance_type', $type)
            ->sum('amount');

        // Khusus deposit, kurangi juga purchase
        $purchase = 0;
        if ($type === 'deposit') {
            $purchase = self::where('user_id', $userId)
                ->where('status', 'success')
                ->where('type', 'purchase')
                ->sum('amount');
        }

        // ✅ NEW: Kurangi porsi legacy withdraw untuk type ini
        $legacyPortion = self::calculateLegacyWithdrawPortion($userId, $type);

        return $income - $withdrawal - $purchase - $legacyPortion;
    }

    /**
     * ✅ NEW: Hitung porsi legacy withdraw untuk tipe tertentu
     * Priority: 100% revenue → 100% commission → deposit
     * 
     * @param int $userId
     * @param string $type (deposit|revenue|commission)
     * @return float
     */
    public static function calculateLegacyWithdrawPortion($userId, $type)
    {
        // Total legacy withdraw
        $legacyTotal = self::getLegacyWithdrawBalance($userId);

        if ($legacyTotal <= 0) {
            return 0;
        }

        // Hitung GROSS income masing-masing
        $revenueIncome = self::where('user_id', $userId)
            ->where('status', 'success')
            ->where('type', 'revenue')
            ->sum('amount');

        $commissionIncome = self::where('user_id', $userId)
            ->where('status', 'success')
            ->where('type', 'commission')
            ->sum('amount');

        $depositIncome = self::where('user_id', $userId)
            ->where('status', 'success')
            ->where('type', 'deposit')
            ->sum('amount');

        // Kurangi dengan withdraw baru yang udah ada source_balance_type
        $revenueNewWithdraw = self::where('user_id', $userId)
            ->whereIn('status', ['success', 'pending'])
            ->where('type', 'withdraw')
            ->where('source_balance_type', 'revenue')
            ->sum('amount');

        $commissionNewWithdraw = self::where('user_id', $userId)
            ->whereIn('status', ['success', 'pending'])
            ->where('type', 'withdraw')
            ->where('source_balance_type', 'commission')
            ->sum('amount');

        $depositNewWithdraw = self::where('user_id', $userId)
            ->whereIn('status', ['success', 'pending'])
            ->where('type', 'withdraw')
            ->where('source_balance_type', 'deposit')
            ->sum('amount');

        // Kurangi purchase untuk deposit
        $purchase = self::where('user_id', $userId)
            ->where('status', 'success')
            ->where('type', 'purchase')
            ->sum('amount');

        // Available balance untuk legacy allocation
        $revenueAvailable = $revenueIncome - $revenueNewWithdraw;
        $commissionAvailable = $commissionIncome - $commissionNewWithdraw;
        $depositAvailable = $depositIncome - $depositNewWithdraw - $purchase;

        // ✅ Distribusi 100% habis dulu revenue → commission → deposit
        $remaining = $legacyTotal;
        $portions = ['revenue' => 0, 'commission' => 0, 'deposit' => 0];

        // 1. 100% Revenue dulu sampai habis
        if ($remaining > 0 && $revenueAvailable > 0) {
            $allocated = min($remaining, $revenueAvailable);
            $portions['revenue'] = $allocated;
            $remaining -= $allocated;
        }

        // 2. 100% Commission sampai habis
        if ($remaining > 0 && $commissionAvailable > 0) {
            $allocated = min($remaining, $commissionAvailable);
            $portions['commission'] = $allocated;
            $remaining -= $allocated;
        }

        // 3. Sisanya baru dari Deposit
        if ($remaining > 0 && $depositAvailable > 0) {
            $allocated = min($remaining, $depositAvailable);
            $portions['deposit'] = $allocated;
            $remaining -= $allocated;
        }

        return $portions[$type] ?? 0;
    }

    /**
     * Get deposit balance (bisa untuk purchase & withdraw)
     */
    public static function getDepositBalance($userId)
    {
        return self::getBalanceByType($userId, 'deposit');
    }

    /**
     * Get revenue balance (hanya untuk withdraw)
     */
    public static function getRevenueBalance($userId)
    {
        return self::getBalanceByType($userId, 'revenue');
    }

    /**
     * Get commission balance (hanya untuk withdraw)
     */
    public static function getCommissionBalance($userId)
    {
        return self::getBalanceByType($userId, 'commission');
    }

    /**
     * Get legacy withdraw (yang belum punya source_balance_type)
     */
    public static function getLegacyWithdrawBalance($userId)
    {
        return self::where('user_id', $userId)
            ->whereIn('status', ['success', 'pending'])
            ->where('type', 'withdraw')
            ->whereNull('source_balance_type')
            ->sum('amount');
    }

    /**
     * Get purchasable balance (hanya dari deposit)
     * ✅ UPDATED: Sekarang udah include legacy withdraw portion
     */
    public static function getPurchasableBalance($userId)
    {
        return self::getDepositBalance($userId);
    }

    /**
     * Get withdrawable balance (deposit + revenue + commission)
     * ✅ UPDATED: Legacy portion udah dihitung di masing-masing balance
     */
    public static function getWithdrawableBalance($userId)
    {
        return self::getDepositBalance($userId)
            + self::getRevenueBalance($userId)
            + self::getCommissionBalance($userId);
    }

    /**
     * Get complete balance breakdown
     * ✅ UPDATED: Tambah info legacy distribution
     */
    public static function getBalanceBreakdown($userId)
    {
        $deposit = self::getDepositBalance($userId);
        $revenue = self::getRevenueBalance($userId);
        $commission = self::getCommissionBalance($userId);
        $legacy = self::getLegacyWithdrawBalance($userId);

        $withdrawable = $deposit + $revenue + $commission;

        // Legacy distribution untuk debugging
        $legacyDistribution = [
            'revenue' => self::calculateLegacyWithdrawPortion($userId, 'revenue'),
            'commission' => self::calculateLegacyWithdrawPortion($userId, 'commission'),
            'deposit' => self::calculateLegacyWithdrawPortion($userId, 'deposit'),
        ];

        return [
            'deposit' => $deposit,
            'revenue' => $revenue,
            'commission' => $commission,
            'purchasable' => $deposit,
            'withdrawable' => max(0, $withdrawable),
            'total' => max(0, $withdrawable),
            'legacy_withdraw' => $legacy,
            'has_legacy_data' => $legacy > 0,
            'legacy_distribution' => $legacyDistribution, // ✅ NEW: Info distribusi legacy
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Validation Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Check if user can purchase
     */
    public static function canPurchase($userId, $amount)
    {
        $available = self::getPurchasableBalance($userId);
        $canPurchase = $available >= $amount;

        return [
            'can_purchase' => $canPurchase,
            'message' => $canPurchase ? 'Sufficient balance' : 'Insufficient deposit balance',
            'available' => $available,
            'required' => $amount,
            'shortage' => max(0, $amount - $available),
        ];
    }

    /**
     * Check if user can withdraw
     */
    public static function canWithdraw($userId, $amount)
    {
        $available = self::getWithdrawableBalance($userId);
        $canWithdraw = $available >= $amount;

        return [
            'can_withdraw' => $canWithdraw,
            'message' => $canWithdraw ? 'Sufficient balance' : 'Insufficient balance',
            'available' => $available,
            'required' => $amount,
            'shortage' => max(0, $amount - $available),
        ];
    }

    /**
     * Calculate withdrawal allocation (priority: revenue -> commission -> deposit)
     */
    public static function calculateWithdrawalAllocation($userId, $amount)
    {
        $balances = [
            'revenue' => self::getRevenueBalance($userId),
            'commission' => self::getCommissionBalance($userId),
            'deposit' => self::getDepositBalance($userId),
        ];

        $allocation = ['revenue' => 0, 'commission' => 0, 'deposit' => 0];
        $remaining = $amount;

        // Allocate berdasarkan priority
        foreach ($balances as $type => $balance) {
            if ($remaining > 0 && $balance > 0) {
                $allocated = min($remaining, $balance);
                $allocation[$type] = $allocated;
                $remaining -= $allocated;
            }
        }

        return [
            'allocation' => $allocation,
            'total_allocated' => $amount - $remaining,
            'remaining_shortage' => $remaining,
            'is_sufficient' => $remaining == 0,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Legacy Methods (Backward Compatibility)
    |--------------------------------------------------------------------------
    */

    /**
     * Legacy balance calculation (untuk validasi/migration)
     * Formula: (deposit + revenue + commission) - (purchase + withdraw)
     */
    public static function calculateUserBalance($userId)
    {
        $income = self::where('user_id', $userId)
            ->where('status', 'success')
            ->whereIn('type', ['deposit', 'revenue', 'commission'])
            ->sum('amount');

        $expenses = self::where('user_id', $userId)
            ->where(function ($query) {
                $query->where('status', 'success')
                    ->orWhere(function ($q) {
                        $q->where('status', 'pending')->where('type', 'withdraw');
                    });
            })
            ->whereIn('type', ['purchase', 'withdraw'])
            ->sum('amount');

        return $income - $expenses;
    }

    /**
     * Validate bahwa new system = legacy system
     * ✅ UPDATED: Sekarang harus sama karena legacy portion udah dihitung
     */
    public static function validateBalanceCalculation($userId)
    {
        $legacy = self::calculateUserBalance($userId);
        $newSystem = self::getWithdrawableBalance($userId);
        $difference = abs($legacy - $newSystem);

        return [
            'legacy_method' => $legacy,
            'new_method' => $newSystem,
            'difference' => $difference,
            'is_equal' => $difference < 0.01,
            'breakdown' => self::getBalanceBreakdown($userId),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Transaction Total Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Get total deposit transactions
     * 
     * @param int $userId
     * @param string|null $status Filter by status (default: 'success')
     * @return float
     */
    public static function getTotalDeposit($userId, $status = 'success')
    {
        $query = self::where('user_id', $userId)->where('type', 'deposit');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->sum('amount');
    }

    /**
     * Get total revenue transactions
     * 
     * @param int $userId
     * @param string|null $status Filter by status (default: 'success')
     * @return float
     */
    public static function getTotalRevenue($userId, $status = 'success')
    {
        $query = self::where('user_id', $userId)->where('type', 'revenue');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->sum('amount');
    }

    /**
     * Get total commission transactions
     * 
     * @param int $userId
     * @param string|null $status Filter by status (default: 'success')
     * @return float
     */
    public static function getTotalCommission($userId, $status = 'success')
    {
        $query = self::where('user_id', $userId)->where('type', 'commission');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->sum('amount');
    }

    /**
     * Get total purchase transactions
     * 
     * @param int $userId
     * @param string|null $status Filter by status (default: 'success')
     * @return float
     */
    public static function getTotalPurchase($userId, $status = 'success')
    {
        $query = self::where('user_id', $userId)->where('type', 'purchase');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->sum('amount');
    }

    /**
     * Get total withdraw transactions
     * 
     * @param int $userId
     * @param string|null $status Filter by status (default: ['success', 'pending'])
     * @return float
     */
    public static function getTotalWithdraw($userId, $status = null)
    {
        $query = self::where('user_id', $userId)->where('type', 'withdraw');

        if ($status === null) {
            // Default: count both success and pending
            $query->whereIn('status', ['success', 'pending']);
        } elseif (is_array($status)) {
            $query->whereIn('status', $status);
        } else {
            $query->where('status', $status);
        }

        return $query->sum('amount');
    }

    /**
     * Get all transaction totals breakdown
     * 
     * @param int $userId
     * @return array
     */
    public static function getTransactionTotals($userId)
    {
        return [
            'deposit' => [
                'success' => self::getTotalDeposit($userId, 'success'),
                'pending' => self::getTotalDeposit($userId, 'pending'),
                'failed' => self::getTotalDeposit($userId, 'failed'),
                'total' => self::getTotalDeposit($userId, null),
            ],
            'revenue' => [
                'success' => self::getTotalRevenue($userId, 'success'),
                'total' => self::getTotalRevenue($userId, null),
            ],
            'commission' => [
                'success' => self::getTotalCommission($userId, 'success'),
                'total' => self::getTotalCommission($userId, null),
            ],
            'purchase' => [
                'success' => self::getTotalPurchase($userId, 'success'),
                'pending' => self::getTotalPurchase($userId, 'pending'),
                'failed' => self::getTotalPurchase($userId, 'failed'),
                'total' => self::getTotalPurchase($userId, null),
            ],
            'withdraw' => [
                'success' => self::getTotalWithdraw($userId, 'success'),
                'pending' => self::getTotalWithdraw($userId, 'pending'),
                'failed' => self::getTotalWithdraw($userId, 'failed'),
                'total' => self::getTotalWithdraw($userId, null),
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeIncome($query)
    {
        return $query->whereIn('type', ['deposit', 'revenue', 'commission']);
    }

    public function scopeExpense($query)
    {
        return $query->whereIn('type', ['purchase', 'withdraw']);
    }
}
