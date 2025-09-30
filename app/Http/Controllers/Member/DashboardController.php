<?php

namespace App\Http\Controllers\Member;

use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Transaction; // Import model Transaction

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $referralCode = $user->refferal_code;
        $referralLink = route('guest.sign-up', ['ref' => $referralCode]);

        // ✅ Balance breakdown untuk debug
        $balanceData = Transaction::getBalanceBreakdown($user->id);
        $balance = $balanceData['total']; // Total withdrawable balance

        // ✅ Validasi (opsional, untuk development/debug)
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
            'balanceData', // ← Kirim breakdown ke view
            'commissionRate',
            'headerText',
            'whatsappNumber',
            'whatsappChannel'
        ));
    }
}
