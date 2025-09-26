<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DistributeProfits extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'profit:distribute 
                            {--dry-run : Run without making changes}
                            {--test : Run in test mode with detailed output}
                            {--user-id= : Test for specific user}
                            {--purchase-id= : Test for specific purchase}';

    /**
     * The description of the console command.
     */
    protected $description = 'Distribute profits to users 24 hours after their exact purchase time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $isTest = $this->option('test');
        $userId = $this->option('user-id');
        $purchaseId = $this->option('purchase-id');

        if ($isDryRun || $isTest) {
            $this->info('🧪 Running in ' . ($isDryRun ? 'DRY RUN' : 'TEST') . ' mode - no changes will be made');
        }

        $currentTime = Carbon::now();
        $this->info('🚀 Starting profit distribution...');
        $this->info('⏰ Current time: ' . $currentTime->format('Y-m-d H:i:s'));

        try {
            if (!$isDryRun && !$isTest) {
                DB::beginTransaction();
            }

            // Build query for purchases
            $query = Transaction::with(['user', 'product'])
                ->where('type', 'purchase')
                ->where('status', 'success');

            // Add filters for testing
            if ($purchaseId) {
                $query->where('id', $purchaseId);
                $this->info("🔍 Testing specific purchase ID: {$purchaseId}");
            } elseif ($userId) {
                $query->where('user_id', $userId);
                $this->info("🔍 Testing specific user ID: {$userId}");
            }

            // Only get purchases that are at least 1 hour old (to avoid immediate processing)
            $oneHourAgo = $currentTime->copy()->subHour();
            $query->where('created_at', '<=', $oneHourAgo);

            $purchases = $query->get();

            if ($purchases->isEmpty()) {
                $this->info('✅ No purchases found to process.');
                return 0;
            }

            $this->info("📊 Found {$purchases->count()} purchase(s) to analyze.");
            $this->newLine();

            $processedCount = 0;
            $skippedCount = 0;
            $totalProfitDistributed = 0;

            foreach ($purchases as $purchase) {
                if ($isTest) {
                    $this->analyzePurchaseForTest($purchase, $currentTime);
                    $this->newLine();
                } else {
                    $result = $this->processPurchaseProfit($purchase, $currentTime, $isDryRun);

                    if ($result['processed']) {
                        $processedCount++;
                        $totalProfitDistributed += $result['profit_amount'];

                        $this->info("💰 User #{$purchase->user_id}: {$result['product_name']} - Day {$result['day_number']}/{$result['total_days']} - Rp " . number_format($result['profit_amount'], 0, ',', '.'));
                    } else {
                        $skippedCount++;
                    }
                }
            }

            if (!$isDryRun && !$isTest && $processedCount > 0) {
                DB::commit();
                Log::info('Profit Distribution Completed', [
                    'processed_transactions' => $processedCount,
                    'skipped_transactions' => $skippedCount,
                    'total_profit_distributed' => $totalProfitDistributed
                ]);
            } elseif (!$isTest) {
                if (!$isDryRun) DB::rollBack();
            }

            if (!$isTest) {
                $this->displaySummary($processedCount, $skippedCount, $totalProfitDistributed);
            }
        } catch (\Exception $e) {
            if (!$isDryRun && !$isTest) {
                DB::rollBack();
            }

            Log::error('Profit Distribution Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->error('❌ Error occurred: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Process profit for a single purchase transaction based on exact time
     */
    private function processPurchaseProfit($purchase, $currentTime, $isDryRun = false)
    {
        $product = $purchase->product;

        if (!$product) {
            $this->warn("⚠️  Product not found for purchase ID: {$purchase->id}");
            return ['processed' => false, 'profit_amount' => 0];
        }

        $purchaseTime = Carbon::parse($purchase->created_at);

        // Calculate how many complete 24-hour periods have passed
        $hoursElapsed = $purchaseTime->diffInHours($currentTime);
        $completeDays = intval($hoursElapsed / 24);

        // Check if we're still within profit distribution period
        if ($completeDays > $product->duration || $completeDays <= 0) {
            return ['processed' => false, 'profit_amount' => 0];
        }

        // Check if it's time for the next profit distribution
        $nextProfitTime = $purchaseTime->copy()->addDays($completeDays);

        // Only distribute if current time is past the exact profit time
        if ($currentTime->lt($nextProfitTime)) {
            return ['processed' => false, 'profit_amount' => 0];
        }

        // Check if profit for this day already distributed
        $revenueReference = 'REV-' . $purchase->reference . '-D' . $completeDays;

        $existingRevenue = Transaction::where('user_id', $purchase->user_id)
            ->where('type', 'revenue')
            ->where('status', 'success')
            ->where('reference', $revenueReference)
            ->exists();

        if ($existingRevenue) {
            return ['processed' => false, 'profit_amount' => 0];
        }

        $dailyProfit = $product->profit;

        if (!$isDryRun) {
            // Create revenue transaction
            Transaction::create([
                'user_id' => $purchase->user_id,
                'product_id' => $product->id,
                'reference' => $revenueReference,
                'amount' => $dailyProfit,
                'type' => 'revenue',
                'status' => 'success',
                'payment_method' => null,
                'payment_proof' => null,
                'approved_by' => null,
            ]);
        }

        return [
            'processed' => true,
            'profit_amount' => $dailyProfit,
            'day_number' => $completeDays,
            'total_days' => $product->duration,
            'product_name' => $product->name,
            'purchase_time' => $purchaseTime->format('Y-m-d H:i:s'),
            'profit_time' => $nextProfitTime->format('Y-m-d H:i:s')
        ];
    }

    /**
     * Analyze purchase for testing purposes
     */
    private function analyzePurchaseForTest($purchase, $currentTime)
    {
        $product = $purchase->product;

        if (!$product) {
            $this->error("❌ Product not found for purchase ID: {$purchase->id}");
            return;
        }

        $purchaseTime = Carbon::parse($purchase->created_at);

        $this->info("🛍️  Purchase Analysis:");
        $this->table(['Field', 'Value'], [
            ['Purchase ID', $purchase->id],
            ['User ID', $purchase->user_id],
            ['Product', $product->name],
            ['Purchase Time', $purchaseTime->format('Y-m-d H:i:s')],
            ['Purchase Amount', 'Rp ' . number_format($purchase->amount, 0, ',', '.')],
            ['Daily Profit', 'Rp ' . number_format($product->profit, 0, ',', '.')],
            ['Duration (Days)', $product->duration],
        ]);

        // Calculate time differences
        $hoursElapsed = $purchaseTime->diffInHours($currentTime);
        $completeDays = intval($hoursElapsed / 24);
        $remainingHours = $hoursElapsed % 24;

        $this->info("⏳ Time Analysis:");
        $this->table(['Metric', 'Value'], [
            ['Hours Elapsed', $hoursElapsed],
            ['Complete 24h Periods', $completeDays],
            ['Remaining Hours', $remainingHours],
            ['Next Profit Due', $completeDays < $product->duration ? $purchaseTime->copy()->addDays($completeDays + 1)->format('Y-m-d H:i:s') : 'Completed'],
        ]);

        // Check profit history - update pattern for new reference format
        $revenueHistory = Transaction::where('user_id', $purchase->user_id)
            ->where('type', 'revenue')
            ->where('status', 'success')
            ->where('product_id', $product->id) // Filter by product_id instead of reference pattern
            ->orderBy('created_at')
            ->get(['reference', 'amount', 'created_at']);

        $this->info("📈 Revenue History:");
        if ($revenueHistory->isNotEmpty()) {
            $historyData = $revenueHistory->map(function ($revenue, $index) {
                return [
                    'Day ' . ($index + 1),
                    'Rp ' . number_format($revenue->amount, 0, ',', '.'),
                    $revenue->created_at->format('Y-m-d H:i:s'),
                    $revenue->reference
                ];
            })->toArray();

            $this->table(['Day', 'Amount', 'Distributed At', 'Reference'], $historyData);
        } else {
            $this->line('  No revenue history found');
        }

        // Profit eligibility analysis
        $this->info("🎯 Current Profit Eligibility:");

        if ($completeDays <= 0) {
            $this->line("  ❌ Not eligible - Less than 24 hours since purchase");
            $hoursRemaining = 24 - $hoursElapsed;
            $this->line("  ⏳ Next profit in: {$hoursRemaining} hours");
        } elseif ($completeDays > $product->duration) {
            $this->line("  ❌ Not eligible - Profit period completed");
            $this->line("  ✅ All {$product->duration} days distributed");
        } else {
            $nextProfitTime = $purchaseTime->copy()->addDays($completeDays);
            $revenueReference = 'REV-' . $purchase->reference . '-D' . $completeDays;

            $alreadyDistributed = Transaction::where('user_id', $purchase->user_id)
                ->where('type', 'revenue')
                ->where('reference', $revenueReference)
                ->exists();

            if ($alreadyDistributed) {
                $this->line("  ❌ Not eligible - Day {$completeDays} already distributed");
            } elseif ($currentTime->gte($nextProfitTime)) {
                $this->line("  ✅ ELIGIBLE - Ready for Day {$completeDays} profit");
                $this->line("  💰 Amount: Rp " . number_format($product->profit, 0, ',', '.'));
                $this->line("  📅 Due since: " . $nextProfitTime->format('Y-m-d H:i:s'));
            } else {
                $this->line("  ⏳ Not yet - Day {$completeDays} due at: " . $nextProfitTime->format('Y-m-d H:i:s'));
                $minutesRemaining = $currentTime->diffInMinutes($nextProfitTime);
                $this->line("  🕐 Time remaining: {$minutesRemaining} minutes");
            }
        }
    }

    /**
     * Display summary results
     */
    private function displaySummary($processedCount, $skippedCount, $totalProfitDistributed)
    {
        $this->newLine();
        $this->info("✅ Process completed!");
        $this->newLine();

        $this->info("📊 Summary:");
        $this->table(['Metric', 'Value'], [
            ['Processed Transactions', $processedCount],
            ['Skipped Transactions', $skippedCount],
            ['Total Profit Distributed', 'Rp ' . number_format($totalProfitDistributed, 0, ',', '.')],
        ]);
    }

    /**
     * Generate simple revenue reference: REV-XXXXXX
     */
    private function generateRevenueReference($purchase, $day)
    {
        // Generate 6 digit number based on purchase ID, user ID, and day
        $number = str_pad(($purchase->id * 1000 + $purchase->user_id * 10 + $day) % 999999, 6, '0', STR_PAD_LEFT);

        // Ensure uniqueness by checking database
        $reference = 'REV-' . $number;
        $counter = 1;

        while (Transaction::where('reference', $reference)->exists()) {
            $number = str_pad(($purchase->id * 1000 + $purchase->user_id * 10 + $day + $counter) % 999999, 6, '0', STR_PAD_LEFT);
            $reference = 'REV-' . $number;
            $counter++;
        }

        return $reference;
    }
}
