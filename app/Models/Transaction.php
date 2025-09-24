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
        // Calculate income (deposit + revenue + commission)
        $income = self::where('user_id', $userId)
            ->where('status', 'success')
            ->whereIn('type', ['deposit', 'revenue', 'commission'])
            ->sum('amount');

        // Calculate expenses (purchase + withdraw)
        $expenses = self::where('user_id', $userId)
            ->where('status', 'success')
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
        $transactions = self::where('user_id', $userId)
            ->where('status', 'success')
            ->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $deposit = $transactions['deposit'] ?? 0;
        $revenue = $transactions['revenue'] ?? 0;
        $commission = $transactions['commission'] ?? 0;
        $purchase = $transactions['purchase'] ?? 0;
        $withdraw = $transactions['withdraw'] ?? 0;

        $totalIncome = $deposit + $revenue + $commission;
        $totalExpenses = $purchase + $withdraw;
        $balance = $totalIncome - $totalExpenses;

        return [
            'income' => [
                'deposit' => $deposit,
                'revenue' => $revenue,
                'commission' => $commission,
                'total' => $totalIncome
            ],
            'expenses' => [
                'purchase' => $purchase,
                'withdraw' => $withdraw,
                'total' => $totalExpenses
            ],
            'balance' => $balance
        ];
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
