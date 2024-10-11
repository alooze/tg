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
        Schema::table('tg_users', function (Blueprint $table) {
            $table->after('status', function (Blueprint $table) {
                $table->integer('last_post_id')->default(1);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tg_users', function (Blueprint $table) {
            $table->dropColumn('last_post_id');
        });
    }
};
