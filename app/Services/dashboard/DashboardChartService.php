<?php

namespace App\Services\Dashboard;

use App\Models\LaporanPemesanan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardChartService
{
    /**
     * Ambil total laporan per bulan untuk SEMUA layanan
     */
    public function getMonthlyTotals(int $year): Collection
    {
        return LaporanPemesanan::select(
                DB::raw('MONTH(tanggal_pemesanan) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('tanggal_pemesanan', $year)
            ->groupBy('month')
            ->pluck('total', 'month');
    }

    /**
     * Ambil total laporan per bulan untuk SATU layanan tertentu
     */
    public function getMonthlyByLayanan(int $layananId, int $year): Collection
    {
        return LaporanPemesanan::select(
                DB::raw('MONTH(tanggal_pemesanan) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('layanan_id', $layananId)
            ->whereYear('tanggal_pemesanan', $year)
            ->groupBy('month')
            ->pluck('total', 'month');
    }

    /**
     * Format data menjadi array untuk Chart.js
     */
    public function formatChartData(Collection $dataByMonth): array
    {
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                   'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $values = [];

        for ($m = 1; $m <= 12; $m++) {
            $values[] = (int) ($dataByMonth[$m] ?? 0);
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * Ambil total laporan per tahun (untuk dropdown filter)
     */
    public function getYearlyTotals(): array
    {
        return LaporanPemesanan::select(
                DB::raw('YEAR(tanggal_pemesanan) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('total', 'year')
            ->toArray();
    }
}
