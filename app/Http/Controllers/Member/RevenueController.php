<?php

namespace App\Http\Controllers\Member;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RevenueController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $revenues = Transaction::with('product')
            ->where('user_id', $userId)
            ->where('type', 'revenue')
            ->where('status', 'success')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($transaction, $index) {
                $transaction->revenue_count = $index + 1;

                // Hitung day in cycle berdasarkan duration
                if ($transaction->product && $transaction->product->duration > 0) {
                    $transaction->day_in_cycle = (($transaction->revenue_count - 1) % $transaction->product->duration) + 1;
                    $transaction->cycle = ceil($transaction->revenue_count / $transaction->product->duration);
                }

                return $transaction;
            });

        return view('member.pages.revenue.index', compact('revenues'));
    }
}
