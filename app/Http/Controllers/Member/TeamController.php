<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;
use App\Models\ReferralUsage;
use App\Models\User;
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

        $commissionRate = Config::where('key', 'team_invite_presentation')
            ->value('value') ?? '35';

        return view('member.pages.team.index', compact('referralUsages', 'currentUser', 'commissionRate'));
    }
}
