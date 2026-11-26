<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_pemesanans', function (Blueprint $table) {
            $table->string('nama_supir')->nullable()->after('titik_pemberhentian');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_pemesanans', function (Blueprint $table) {
            $table->dropColumn('nama_supir');
        });
    }
};
