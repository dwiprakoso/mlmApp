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
        Schema::create('referral_usages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_referral');
            $table->unsignedBigInteger('used_by');
            $table->timestamps();

            $table->foreign('user_referral')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('used_by')->references('id')->on('users')->onDelete('cascade');

            $table->index('user_referral');
            $table->index('used_by');

            $table->unique('used_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_usages');
    }
};
