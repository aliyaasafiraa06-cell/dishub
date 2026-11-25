<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Memanggil seeder User dan Layanan secara berurutan
        $this->call([
            UserSeeder::class,
            LayananSeeder::class,
        ]);
    }
}
