<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Services\Dashboard\DashboardChartService;
use App\Services\Laporan\LaporanService;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function show(
        string $slug,
        Request $request,
        LaporanService $laporanService,
        DashboardChartService $chartService
    ) {
        // Cari layanan berdasarkan slug (angkutan-listrik, bus-tingkat, dll)
        $layanan = Layanan::bySlug($slug)->firstOrFail();

        $year = (int) $request->get('year', now()->year);

        // Simpan filter untuk dikirim ke view
        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'year' => $request->get('year'),
        ];

        // Ambil data laporan dengan filter
        $query = $laporanService->queryForLayanan($layanan->id, $filters);
        $laporan = $laporanService->paginate($query);

        // Ambil ringkasan status (Total, Menunggu, Diproses, dll)
        $statusCounts = $laporanService->getStatusCounts($layanan->id, $year);

        // Range tahun tetap (2023 s/d tahun depan)
        $startYear = 2023;
        $endYear = (int) now()->year + 1;
        $availableYears = range($endYear, $startYear);

        // Ambil data grafik bulanan khusus layanan ini
        $monthlyData = $chartService->getMonthlyByLayanan($layanan->id, $year);
        $chartData = $chartService->formatChartData($monthlyData);

        return view('layanan.show', compact(
            'layanan',
            'laporan',
            'statusCounts',
            'chartData',
            'year',
            'availableYears',
            'filters'
        ));
    }
}
