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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Type wallet: 'bank' atau 'ewallet'
            $table->enum('wallet_type', ['bank', 'ewallet']);

            // Untuk bank
            $table->string('bank_name')->nullable(); // BCA, BNI, Mandiri, dll
            $table->string('bank_account')->nullable(); // nomor rekening
            $table->string('account_name')->nullable(); // nama pemegang rekening

            // Untuk e-wallet
            $table->string('ewallet_provider')->nullable(); // GoPay, OVO, DANA, dll
            $table->string('ewallet_number')->nullable(); // nomor hp atau ID e-wallet
            $table->string('ewallet_name')->nullable(); // nama akun e-wallet

            // Field tambahan
            $table->boolean('is_primary')->default(false); // wallet utama user
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable(); // catatan tambahan

            $table->timestamps();

            // Index untuk performa
            $table->index(['user_id', 'wallet_type']);
            $table->index(['user_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
