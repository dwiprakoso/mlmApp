<?php

namespace App\Http\Controllers\Member;

use App\Models\User;
use App\Models\Config;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\ReferralUsage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();

        $referralUsages = ReferralUsage::with('referee')
            ->where('user_referral', $currentUser->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalDepositFromReferral = ReferralUsage::where('user_referral', $currentUser->id)
            ->sum('deposit_count');

        $totalDepositFromTransaction = Transaction::where('user_id', $currentUser->id)
            ->where('type', 'deposit')
            ->where('status', 'success')
            ->count();

        $totalDepositCount = $totalDepositFromReferral + $totalDepositFromTransaction;

        $commissionRate = Config::where('key', 'team_invite_presentation')
            ->value('value') ?? '35';

        return view('member.pages.team.index', compact(
            'referralUsages',
            'currentUser',
            'commissionRate',
            'totalDepositCount'
        ));
    }
}
