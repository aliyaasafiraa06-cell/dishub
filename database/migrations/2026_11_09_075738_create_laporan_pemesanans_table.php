<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_pemesanans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_laporan')->unique();
            $table->foreignId('layanan_id')->constrained('layanans')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_pemesan');
            $table->string('instansi');
            $table->string('no_telepon')->nullable();
            $table->date('tanggal_pemesanan');
            $table->date('tanggal_pelaksanaan')->nullable();
            $table->unsignedInteger('jumlah_pemesanan');
            $table->text('keperluan');
            $table->string('status')->default('Menunggu');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Index untuk mempercepat pencarian
            $table->index(['layanan_id', 'tanggal_pemesanan']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_pemesanans');
    }
};
