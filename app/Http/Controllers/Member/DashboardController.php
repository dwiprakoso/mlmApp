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

        $balance = Transaction::calculateUserBalance($user->id);

        $commissionRate = Config::where('key', 'team_invite_presentation')
            ->value('value') ?? '10';
        $headerText = Config::where('key', 'header_text')
            ->value('value') ?? 'Investasi pertambangan';

        // Ambil config WhatsApp
        $whatsappNumber = Config::where('key', 'whatsapp_number')
            ->value('value') ?? '6281266818738';
        $whatsappChannel = Config::where('key', 'whatsapp_channel')
            ->value('value') ?? 'https://whatsapp.com/channel/0029Vb6sugA47Xe9';

        return view('member.pages.dasboard.index', compact(
            'user',
            'referralCode',
            'referralLink',
            'balance',
            'commissionRate',
            'headerText',
            'whatsappNumber',
            'whatsappChannel'
        ));
    }
    // public function index()
    // {
    //     $user = auth()->user();

    //     // 🔍 DEBUGGING: Bandingkan kedua method
    //     $validation = Transaction::validateBalanceCalculation($user->id);

    //     dd($validation);
    //     // Cek hasil:
    //     // - 'is_equal' harus TRUE
    //     // - 'difference' harus 0 atau mendekati 0
    //     // - 'legacy_method' dan 'new_method' harus sama
    // }
}
