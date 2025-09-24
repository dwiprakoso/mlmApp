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
        Schema::table('transactions', function (Blueprint $table) {
            // Tambah wallet_id setelah user_id
            $table->foreignId('wallet_id')->nullable()->after('user_id')->constrained('wallets')->nullOnDelete();

            // Tambah index untuk performa
            $table->index(['user_id', 'wallet_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Drop foreign key constraint dan index dulu
            $table->dropForeign(['wallet_id']);
            $table->dropIndex(['user_id', 'wallet_id']);

            // Baru drop column
            $table->dropColumn('wallet_id');
        });
    }
};
