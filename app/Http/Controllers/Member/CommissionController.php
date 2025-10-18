<?php

namespace App\Http\Controllers\Member;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommissionController extends Controller
{
    public function index()
    {
        $commissions = Transaction::with(['relatedTransaction', 'sourceUser'])
            ->where('type', 'commission')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('member.pages.commission.index', compact('commissions'));
    }
}
