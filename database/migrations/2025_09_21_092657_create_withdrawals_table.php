<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique(); // Generate unique ID
            $table->decimal('amount', 15, 2); // Jumlah withdrawal
            $table->decimal('fee', 15, 2)->default(0); // Fee/pajak (10%)
            $table->decimal('net_amount', 15, 2); // Amount - fee
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['bank', 'ewallet']); // Sesuai wallet type

            // Bank details (jika menggunakan bank)
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('account_name')->nullable();

            // E-wallet details (jika menggunakan e-wallet)
            $table->string('ewallet_provider')->nullable();
            $table->string('ewallet_number')->nullable();
            $table->string('ewallet_name')->nullable();

            $table->text('notes')->nullable(); // Catatan dari user
            $table->text('admin_notes')->nullable(); // Catatan dari admin
            $table->text('rejection_reason')->nullable(); // Alasan jika ditolak

            $table->timestamp('requested_at'); // Kapan request dibuat
            $table->timestamp('processed_at')->nullable(); // Kapan diproses admin
            $table->timestamp('completed_at')->nullable(); // Kapan selesai

            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['status', 'requested_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('withdrawals');
    }
};
