<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    public function index()
    {
        $withdrawTransactions = Transaction::where('type', 'withdraw')
            ->with(['user', 'wallet'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pages.withdraw.index', compact('withdrawTransactions'));
    }
}
