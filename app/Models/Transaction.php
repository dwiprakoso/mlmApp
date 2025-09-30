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
    | Balance Calculation by Type
    |--------------------------------------------------------------------------
    */

    /**
     * Get deposit balance (available for purchase and withdraw)
     * Formula: deposit - (purchase + withdraw from deposit)
     *
     * @param int $userId
     * @return float
     */
    public static function getDepositBalance($userId)
    {
        // Total deposit income
        $deposit = self::where('user_id', $userId)
            ->where('status', 'success')
            ->where('type', 'deposit')
            ->sum('amount');

        // Purchase (always from deposit)
        $purchase = self::where('user_id', $userId)
            ->where('status', 'success')
            ->where('type', 'purchase')
            ->sum('amount');

        // Withdraw from deposit (success + pending)
        $withdrawFromDeposit = self::where('user_id', $userId)
            ->where(function ($query) {
                $query->where('status', 'success')
                    ->orWhere('status', 'pending');
            })
            ->where('type', 'withdraw')
            ->where('source_balance_type', 'deposit')
            ->sum('amount');

        return $deposit - $purchase - $withdrawFromDeposit;
    }

    /**
     * Get revenue balance (available for withdraw only)
     * Formula: revenue - withdraw from revenue
     *
     * @param int $userId
     * @return float
     */
    public static function getRevenueBalance($userId)
    {
        // Total revenue income
        $revenue = self::where('user_id', $userId)
            ->where('status', 'success')
            ->where('type', 'revenue')
            ->sum('amount');

        // Withdraw from revenue (success + pending)
        $withdrawFromRevenue = self::where('user_id', $userId)
            ->where(function ($query) {
                $query->where('status', 'success')
                    ->orWhere('status', 'pending');
            })
            ->where('type', 'withdraw')
            ->where('source_balance_type', 'revenue')
            ->sum('amount');

        return $revenue - $withdrawFromRevenue;
    }

    /**
     * Get commission balance (available for withdraw only)
     * Formula: commission - withdraw from commission
     *
     * @param int $userId
     * @return float
     */
    public static function getCommissionBalance($userId)
    {
        // Total commission income
        $commission = self::where('user_id', $userId)
            ->where('status', 'success')
            ->where('type', 'commission')
            ->sum('amount');

        // Withdraw from commission (success + pending)
        $withdrawFromCommission = self::where('user_id', $userId)
            ->where(function ($query) {
                $query->where('status', 'success')
                    ->orWhere('status', 'pending');
            })
            ->where('type', 'withdraw')
            ->where('source_balance_type', 'commission')
            ->sum('amount');

        return $commission - $withdrawFromCommission;
    }

    /**
     * Get purchasable balance (only from deposit)
     *
     * @param int $userId
     * @return float
     */
    public static function getPurchasableBalance($userId)
    {
        return self::getDepositBalance($userId);
    }

    /**
     * Get withdrawable balance (revenue + commission + deposit)
     *
     * @param int $userId
     * @return float
     */
    public static function getWithdrawableBalance($userId)
    {
        return self::getRevenueBalance($userId)
            + self::getCommissionBalance($userId)
            + self::getDepositBalance($userId);
    }

    /**
     * Get detailed balance breakdown by source type
     *
     * @param int $userId
     * @return array
     */
    public static function getBalanceBreakdown($userId)
    {
        $depositBalance = self::getDepositBalance($userId);
        $revenueBalance = self::getRevenueBalance($userId);
        $commissionBalance = self::getCommissionBalance($userId);

        return [
            'deposit' => $depositBalance,
            'revenue' => $revenueBalance,
            'commission' => $commissionBalance,
            'purchasable' => $depositBalance,
            'withdrawable' => $depositBalance + $revenueBalance + $commissionBalance,
            'total' => $depositBalance + $revenueBalance + $commissionBalance,
        ];
    }

    /**
     * Validate if user can make a purchase
     *
     * @param int $userId
     * @param float $amount
     * @return array ['can_purchase' => bool, 'message' => string, 'available' => float]
     */
    public static function canPurchase($userId, $amount)
    {
        $availableBalance = self::getPurchasableBalance($userId);

        return [
            'can_purchase' => $availableBalance >= $amount,
            'message' => $availableBalance >= $amount
                ? 'Sufficient balance'
                : 'Insufficient deposit balance',
            'available' => $availableBalance,
            'required' => $amount,
            'shortage' => max(0, $amount - $availableBalance),
        ];
    }

    /**
     * Validate if user can make a withdrawal
     *
     * @param int $userId
     * @param float $amount
     * @return array ['can_withdraw' => bool, 'message' => string, 'available' => float]
     */
    public static function canWithdraw($userId, $amount)
    {
        $availableBalance = self::getWithdrawableBalance($userId);

        return [
            'can_withdraw' => $availableBalance >= $amount,
            'message' => $availableBalance >= $amount
                ? 'Sufficient balance'
                : 'Insufficient balance',
            'available' => $availableBalance,
            'required' => $amount,
            'shortage' => max(0, $amount - $availableBalance),
        ];
    }

    /**
     * Calculate withdrawal allocation (priority: revenue -> commission -> deposit)
     *
     * @param int $userId
     * @param float $amount
     * @return array
     */
    public static function calculateWithdrawalAllocation($userId, $amount)
    {
        $revenueBalance = self::getRevenueBalance($userId);
        $commissionBalance = self::getCommissionBalance($userId);
        $depositBalance = self::getDepositBalance($userId);

        $allocation = [
            'revenue' => 0,
            'commission' => 0,
            'deposit' => 0,
        ];

        $remaining = $amount;

        // Priority 1: Revenue
        if ($remaining > 0 && $revenueBalance > 0) {
            $fromRevenue = min($remaining, $revenueBalance);
            $allocation['revenue'] = $fromRevenue;
            $remaining -= $fromRevenue;
        }

        // Priority 2: Commission
        if ($remaining > 0 && $commissionBalance > 0) {
            $fromCommission = min($remaining, $commissionBalance);
            $allocation['commission'] = $fromCommission;
            $remaining -= $fromCommission;
        }

        // Priority 3: Deposit
        if ($remaining > 0 && $depositBalance > 0) {
            $fromDeposit = min($remaining, $depositBalance);
            $allocation['deposit'] = $fromDeposit;
            $remaining -= $fromDeposit;
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
    | Legacy Helper Methods (kept for backward compatibility)
    |--------------------------------------------------------------------------
    */

    /**
     * Calculate user balance based on successful transactions
     * Formula: (deposit + revenue + commission) - (purchase + withdraw)
     *
     * @param int $userId
     * @return float
     */
    public static function calculateUserBalance($userId)
    {
        // Calculate income (deposit + revenue + commission) - only success
        $income = self::where('user_id', $userId)
            ->where('status', 'success')
            ->whereIn('type', ['deposit', 'revenue', 'commission'])
            ->sum('amount');

        // Calculate expenses (purchase + withdraw) - include pending withdrawals
        $expenses = self::where('user_id', $userId)
            ->where(function ($query) {
                $query->where('status', 'success')
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('status', 'pending')
                            ->where('type', 'withdraw');
                    });
            })
            ->whereIn('type', ['purchase', 'withdraw'])
            ->sum('amount');

        return $income - $expenses;
    }

    /**
     * Get user balance breakdown
     *
     * @param int $userId
     * @return array
     */
    public static function getUserBalanceBreakdown($userId)
    {
        // Get successful transactions breakdown
        $successTransactions = self::where('user_id', $userId)
            ->where('status', 'success')
            ->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        // Get pending withdrawals
        $pendingWithdrawals = self::where('user_id', $userId)
            ->where('status', 'pending')
            ->where('type', 'withdraw')
            ->sum('amount');

        $deposit = $successTransactions['deposit'] ?? 0;
        $revenue = $successTransactions['revenue'] ?? 0;
        $commission = $successTransactions['commission'] ?? 0;
        $successPurchase = $successTransactions['purchase'] ?? 0;
        $successWithdraw = $successTransactions['withdraw'] ?? 0;

        $totalIncome = $deposit + $revenue + $commission;
        $totalExpenses = $successPurchase + $successWithdraw + $pendingWithdrawals;
        $balance = $totalIncome - $totalExpenses;

        return [
            'income' => [
                'deposit' => $deposit,
                'revenue' => $revenue,
                'commission' => $commission,
                'total' => $totalIncome
            ],
            'expenses' => [
                'purchase' => $successPurchase,
                'withdraw' => [
                    'success' => $successWithdraw,
                    'pending' => $pendingWithdrawals,
                    'total' => $successWithdraw + $pendingWithdrawals
                ],
                'total' => $totalExpenses
            ],
            'balance' => $balance
        ];
    }

    /**
     * Get available balance (excluding pending withdrawals)
     *
     * @param int $userId
     * @return float
     */
    public static function getAvailableBalance($userId)
    {
        return self::calculateUserBalance($userId);
    }

    /**
     * Scope for successful transactions only
     */
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for income transactions (deposit, revenue, commission)
     */
    public function scopeIncome($query)
    {
        return $query->whereIn('type', ['deposit', 'revenue', 'commission']);
    }

    /**
     * Scope for expense transactions (purchase, withdraw)
     */
    public function scopeExpense($query)
    {
        return $query->whereIn('type', ['purchase', 'withdraw']);
    }

    /**
     * Get sum of each transaction type with success status
     *
     * @param int|null $userId Optional - filter by user_id
     * @return array
     */
    public static function getTransactionSummary($userId = null)
    {
        $query = self::where('status', 'success');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $summary = $query->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        return [
            'deposit' => $summary['deposit'] ?? 0,
            'withdraw' => $summary['withdraw'] ?? 0,
            'purchase' => $summary['purchase'] ?? 0,
            'revenue' => $summary['revenue'] ?? 0,
            'commission' => $summary['commission'] ?? 0,
        ];
    }

    /**
     * Get total for specific transaction type with success status
     *
     * @param string $type
     * @param int|null $userId
     * @return float
     */
    public static function getTotalByType($type, $userId = null)
    {
        $query = self::where('status', 'success')
            ->where('type', $type);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->sum('amount');
    }

    /**
     * Get individual totals
     */
    public static function getTotalDeposit($userId = null)
    {
        return self::getTotalByType('deposit', $userId);
    }

    public static function getTotalWithdraw($userId = null)
    {
        return self::getTotalByType('withdraw', $userId);
    }

    public static function getTotalPurchase($userId = null)
    {
        return self::getTotalByType('purchase', $userId);
    }

    public static function getTotalRevenue($userId = null)
    {
        return self::getTotalByType('revenue', $userId);
    }

    public static function getTotalCommission($userId = null)
    {
        return self::getTotalByType('commission', $userId);
    }
}
