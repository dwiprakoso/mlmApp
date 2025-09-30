<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('referral_usages', function (Blueprint $table) {
            $table->boolean('is_deposit')->default(false)->after('used_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referral_usages', function (Blueprint $table) {
            $table->dropColumn('is_deposit');
        });
    }
};
