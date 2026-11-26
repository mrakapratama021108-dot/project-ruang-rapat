<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('tautan_zoom', 500)->nullable()->after('fasilitas_tambahan');
            $table->string('kode_zoom', 100)->nullable()->after('tautan_zoom');
            $table->string('password_zoom', 100)->nullable()->after('kode_zoom');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['tautan_zoom', 'kode_zoom', 'password_zoom']);
        });
    }
};