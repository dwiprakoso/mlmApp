<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('referral_usages', function (Blueprint $table) {
            $table->integer('deposit_count')->default(0)->after('used_by');
        });

        // Migrate data: convert boolean to integer (true = 1, false = 0)
        DB::table('referral_usages')->update([
            'deposit_count' => DB::raw('CAST(is_deposit AS UNSIGNED)')
        ]);

        Schema::table('referral_usages', function (Blueprint $table) {
            $table->dropColumn('is_deposit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referral_usages', function (Blueprint $table) {
            $table->boolean('is_deposit')->default(false)->after('used_by');
        });

        // Migrate data back: convert integer to boolean (> 0 = true, 0 = false)
        DB::table('referral_usages')->update([
            'is_deposit' => DB::raw('IF(deposit_count > 0, 1, 0)')
        ]);

        Schema::table('referral_usages', function (Blueprint $table) {
            $table->dropColumn('deposit_count');
        });
    }
};
