<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        // Get transactions with type 'purchase' using Eloquent
        $transactions = Transaction::with(['user', 'product'])
            ->where('type', 'purchase')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pages.transaction.index', compact('transactions'));
    }
}
