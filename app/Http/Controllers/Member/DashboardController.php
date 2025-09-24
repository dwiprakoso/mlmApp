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

        $balance = Transaction::where('user_id', $user->id)
            ->where('type', 'deposit')
            ->where('status', 'success')
            ->sum('amount');

        $commissionRate = Config::where('key', 'team_invite_presentation')
            ->value('value') ?? '10';

        return view('member.pages.dasboard.index', compact('user', 'referralCode', 'referralLink', 'balance', 'commissionRate'));
    }
}
