<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        $layanans = [
            [
                'nama_layanan' => 'Angkutan Listrik',
                'slug' => 'angkutan-listrik',
                'icon' => 'bi-ev-front',
                'deskripsi' => 'Layanan angkutan umum berbasis kendaraan listrik.',
            ],
            [
                'nama_layanan' => 'Bus Tingkat',
                'slug' => 'bus-tingkat',
                'icon' => 'bi-bus-front',
                'deskripsi' => 'Layanan wisata menggunakan bus tingkat.',
            ],
            [
                'nama_layanan' => 'Bus Edukasi',
                'slug' => 'bus-edukasi',
                'icon' => 'bi-mortarboard',
                'deskripsi' => 'Layanan bus untuk kegiatan edukasi dan sekolah.',
            ],
            [
                'nama_layanan' => 'Kereta Jaladara',
                'slug' => 'kereta-jaladara',
                'icon' => 'bi-train-front',
                'deskripsi' => 'Layanan kereta wisata khas Kota Surakarta.',
            ],
        ];

        foreach ($layanans as $layanan) {
            DB::table('layanans')->insert(array_merge($layanan, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
