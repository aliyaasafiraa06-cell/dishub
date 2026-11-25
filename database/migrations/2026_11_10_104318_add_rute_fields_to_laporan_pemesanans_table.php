<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_pemesanans', function (Blueprint $table) {
            $table->string('rute')->nullable()->after('layanan_id');
            $table->time('jam_berangkat')->nullable()->after('tanggal_pelaksanaan');
            $table->text('titik_pemberhentian')->nullable()->after('jam_berangkat');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_pemesanans', function (Blueprint $table) {
            $table->dropColumn(['rute', 'jam_berangkat', 'titik_pemberhentian']);
        });
    }
};
