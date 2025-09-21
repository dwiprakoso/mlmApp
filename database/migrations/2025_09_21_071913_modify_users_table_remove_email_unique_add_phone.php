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
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->string('email')->nullable()->change();
            $table->dropColumn('email_verified_at');
            $table->string('phone')->nullable()->after('status');
        });
        DB::statement("UPDATE users SET phone = CONCAT('temp_', id) WHERE phone IS NULL OR phone = ''");

        Schema::table('users', function (Blueprint $table) {
            $table->unique('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['phone']);
            $table->timestamp('email_verified_at')->nullable();
            $table->dropColumn('phone');
            $table->string('email')->nullable(false)->change();
            $table->unique('email');
        });
    }
};
