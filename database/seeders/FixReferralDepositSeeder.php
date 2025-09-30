<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;

class FixReferralDepositSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Log::info('Starting FixReferralDepositSeeder');

        // Ambil semua referral_usages yang belum di-set is_deposit
        $referralUsages = DB::table('referral_usages')
            ->where(function ($query) {
                $query->where('is_deposit', false)
                    ->orWhereNull('is_deposit');
            })
            ->get();

        Log::info('Found referral usages to process', [
            'count' => $referralUsages->count()
        ]);

        $updatedCount = 0;
        $skippedCount = 0;

        foreach ($referralUsages as $referralUsage) {
            // Cek apakah user ini punya deposit sukses
            $hasSuccessfulDeposit = Transaction::where('user_id', $referralUsage->used_by)
                ->where('type', 'deposit')
                ->where('status', 'success')
                ->exists();

            if ($hasSuccessfulDeposit) {
                // Update is_deposit jadi true
                DB::table('referral_usages')
                    ->where('id', $referralUsage->id)
                    ->update([
                        'is_deposit' => true,
                        'updated_at' => now()
                    ]);

                $updatedCount++;

                Log::info('Updated referral usage is_deposit', [
                    'used_by' => $referralUsage->used_by,
                    'referral_owner' => $referralUsage->user_referral
                ]);

                $this->command->info("✓ Updated is_deposit for user {$referralUsage->used_by}");
            } else {
                $skippedCount++;
                Log::info('No successful deposit found, skipping', [
                    'used_by' => $referralUsage->used_by
                ]);
            }
        }

        Log::info('FixReferralDepositSeeder completed', [
            'updated_count' => $updatedCount,
            'skipped_count' => $skippedCount
        ]);

        $this->command->info("\n=== Summary ===");
        $this->command->info("Referral usages updated: {$updatedCount}");
        $this->command->info("Skipped (no deposit): {$skippedCount}");
    }
}
