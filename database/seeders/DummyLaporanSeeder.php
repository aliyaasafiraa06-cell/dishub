<?php

namespace Database\Seeders;

use App\Models\LaporanPemesanan;
use App\Models\Layanan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyLaporanSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $layanans = Layanan::all()->keyBy('slug');

        // 1) Hapus semua data dummy lama (kode DUMMY-*).
        //    Laporan asli/manual TIDAK ikut terhapus.
        $deleted = LaporanPemesanan::where('kode_laporan', 'like', 'DUMMY-%')->delete();

        // 2) Normalisasi laporan yang masih berstatus lama
        //    (Menunggu/Diproses/Ditolak/Disetujui) menjadi Sewa.
        //    Ganti 'Sewa' menjadi 'Ritel' jika itu yang diinginkan.
        $fixed = LaporanPemesanan::whereNotIn('status', LaporanPemesanan::STATUS_OPTIONS)
            ->update(['status' => 'Sewa']);

        $namaPemesan = [
            'Andi Pratama', 'Siti Rahmawati', 'Budi Santoso', 'Rina Oktaviani',
            'Dimas Saputra', 'Agus Setiawan', 'Maya Puspitasari', 'Rudi Hermawan',
            'Nanda Putri', 'Fajar Nugroho', 'Dewi Anggraini', 'Rizky Firmansyah',
            'Lina Marlina', 'Wahyu Hidayat', 'Intan Permata', 'Eko Prasetyo',
            'Nadia Safitri', 'Arif Wibowo', 'Putri Amelia', 'Yoga Pratama',
        ];

        $instansi = [
            'Dinas Pendidikan', 'SMP Negeri 4 Surakarta', 'Kecamatan Banjarsari',
            'Universitas Sebelas Maret', 'Dinas Pariwisata', 'SMK Negeri 2 Surakarta',
            'SD Negeri Manahan', 'Dinas Kebudayaan', 'Komunitas Wisata Solo',
            'SMA Negeri 1 Surakarta', 'SD Negeri 3 Laweyan', 'SMP Negeri 5 Surakarta',
            'TK Pelita Bangsa', 'SD Negeri 2 Banjarsari', 'SMP Negeri 8 Surakarta',
            'Universitas Muhammadiyah Surakarta', 'Komunitas Sejarah Solo',
            'SMA Negeri 3 Surakarta', 'Dinas Perhubungan', 'Kecamatan Laweyan',
        ];

        // Total 120 laporan tersebar sepanjang tahun 2026.
        $jumlahPerBulan = [5, 7, 8, 9, 10, 12, 14, 13, 12, 11, 10, 9];

        // Jenis pemesanan: 72 Sewa + 48 Ritel = 120.
        $statuses = array_merge(
            array_fill(0, 72, 'Sewa'),
            array_fill(0, 48, 'Ritel')
        );

        mt_srand(2026);
        shuffle($statuses);

        $slugs = ['angkutan-listrik', 'bus-tingkat', 'bus-edukasi', 'kereta-jaladara'];

        $titikPemberhentian = [
            'Kantor Dishub Surakarta', 'Terminal Tirtonadi', 'Benteng Vastenburg',
            'Stasiun Purwosari', 'Balai Kota Surakarta', 'Manahan',
            'Kawasan Keraton Surakarta', 'Monumen Pers',
        ];

        DB::transaction(function () use (
            $admin, $layanans, $namaPemesan, $instansi,
            $jumlahPerBulan, $statuses, $slugs, $titikPemberhentian
        ) {
            $index = 0;

            foreach ($jumlahPerBulan as $bulanIndex => $jumlah) {
                $bulan = $bulanIndex + 1;

                for ($j = 0; $j < $jumlah; $j++) {
                    $layananSlug = $slugs[$index % 4];
                    $layanan = $layanans->get($layananSlug);

                    if (!$layanan) {
                        throw new \RuntimeException("Layanan {$layananSlug} tidak ditemukan.");
                    }

                    $ruteOptions = LaporanPemesanan::RUTE_OPTIONS[$layananSlug];
                    $rute = $ruteOptions[$index % count($ruteOptions)];

                    $hari = 2 + (($j * 5 + $index) % 25);
                    $tanggalPemesanan = Carbon::create(2026, $bulan, $hari);
                    $tanggalPelaksanaan = $tanggalPemesanan->copy()->addDays(3 + ($index % 10));

                    LaporanPemesanan::create([
                        'kode_laporan' => 'DUMMY-2026-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                        'layanan_id' => $layanan->id,
                        'user_id' => $admin->id,
                        'nama_pemesan' => $namaPemesan[$index % count($namaPemesan)],
                        'instansi' => $instansi[$index % count($instansi)],
                        'no_telepon' => '0812345678' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                        'tanggal_pemesanan' => $tanggalPemesanan->format('Y-m-d'),
                        'tanggal_pelaksanaan' => $tanggalPelaksanaan->format('Y-m-d'),
                        'jam_berangkat' => sprintf('%02d:%02d', 7 + ($index % 5), ($index % 4) * 15),
                        'rute' => $rute,
                        'titik_pemberhentian' => $titikPemberhentian[$index % count($titikPemberhentian)],
                        'jumlah_pemesanan' => 10 + (($index * 7) % 41),
                        'keperluan' => 'Kegiatan kunjungan, wisata, edukasi, dan perjalanan rombongan.',
                        'status' => $statuses[$index],
                        'keterangan' => 'Data dummy untuk pengujian aplikasi Dishub.',
                    ]);

                    $index++;
                }
            }
        });

        $this->command->info(
            "Selesai: {$deleted} dummy lama dihapus, {$fixed} status lama dinormalisasi, 120 dummy baru dibuat (72 Sewa, 48 Ritel)."
        );
    }
}
