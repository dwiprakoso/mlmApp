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
        'wallet_id',
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

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
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
}
