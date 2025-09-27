<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('source_user_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('related_transaction_id')->nullable()->after('source_user_id');

            // Jika ingin ada relasi foreign key:
            // $table->foreign('related_transaction_id')->references('id')->on('transactions')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['source_user_id', 'related_transaction_id']);
        });
    }
};
