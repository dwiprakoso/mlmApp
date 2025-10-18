<?php

namespace App\Http\Controllers\Member;

use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $referralCode = $user->refferal_code;
        $referralLink = route('guest.sign-up', ['ref' => $referralCode]);

        $balanceData = Transaction::getBalanceBreakdown($user->id);
        $balance = $balanceData['total'];

        if (config('app.debug')) {
            $validation = Transaction::validateBalanceCalculation($user->id);
            Log::info('Balance Validation', [
                'user_id' => $user->id,
                'legacy' => $validation['legacy_method'],
                'new_system' => $validation['new_method'],
                'is_equal' => $validation['is_equal'],
                'difference' => $validation['difference']
            ]);
        }

        $commissionRate = Config::where('key', 'team_invite_presentation')
            ->value('value') ?? '10';
        $headerText = Config::where('key', 'header_text')
            ->value('value') ?? 'Investasi pertambangan';
        $whatsappNumber = Config::where('key', 'whatsapp_number')
            ->value('value') ?? '6281266818738';
        $whatsappChannel = Config::where('key', 'whatsapp_channel')
            ->value('value') ?? 'https://whatsapp.com/channel/0029Vb6sugA47Xe9';

        return view('member.pages.dasboard.index', compact(
            'user',
            'referralCode',
            'referralLink',
            'balance',
            'balanceData',
            'commissionRate',
            'headerText',
            'whatsappNumber',
            'whatsappChannel'
        ));
    }
}
