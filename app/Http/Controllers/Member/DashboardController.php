<?php

namespace App\Http\Controllers\Member;

use App\Models\Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction; // Import model Transaction

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $referralCode = $user->refferal_code;
        $referralLink = route('guest.sign-up', ['ref' => $referralCode]);



        // Hitung total deposit yang sukses
        $totalDeposit = Transaction::where('user_id', $user->id)
            ->where('type', 'deposit')
            ->where('status', 'success')
            ->sum('amount');

        // Hitung total withdrawal yang sudah sukses atau pending
        $totalWithdraw = Transaction::where('user_id', $user->id)
            ->where('type', 'withdraw')
            ->whereIn('status', ['success', 'pending'])
            ->sum('amount');
        $balance = $totalDeposit - $totalWithdraw;

        $commissionRate = Config::where('key', 'team_invite_presentation')
            ->value('value') ?? '10';
        $headerText = Config::where('key', 'header_text')
            ->value('value') ?? '10';

        return view('member.pages.dasboard.index', compact('user', 'referralCode', 'referralLink', 'balance', 'commissionRate', 'headerText'));
    }
}
