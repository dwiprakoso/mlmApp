<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('otp_password', function (Blueprint $table) {
            // Bikin phone nullable dulu (kalau mau keep backward compatibility)
            $table->string('phone')->nullable()->change();

            // Tambah email
            $table->string('email')->nullable()->after('phone');

            // Index
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::table('otp_password', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->string('phone')->nullable(false)->change();
            $table->dropIndex(['email']);
        });
    }
};
