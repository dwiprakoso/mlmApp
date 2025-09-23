<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReferralUsage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index()
    {
        // Ambil user yang sedang login
        $currentUser = Auth::user();

        // Ambil semua referral usage yang user_referral-nya adalah user yang sedang login
        // Dan include relationship ke user yang menggunakan referral (referee)
        $referralUsages = ReferralUsage::with('referee')
            ->where('user_referral', $currentUser->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Atau bisa juga pakai relationship dari User model
        // $referralUsages = $currentUser->referrals()->with('referee')->orderBy('created_at', 'desc')->get();

        return view('member.pages.team.index', compact('referralUsages', 'currentUser'));
    }
}
