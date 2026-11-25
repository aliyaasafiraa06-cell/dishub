<?php

namespace App\Http\Controllers;

use App\Models\LaporanPemesanan;
use App\Models\Layanan;
use App\Services\Dashboard\DashboardChartService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, DashboardChartService $chartService)
    {
        $year = (int) $request->get('year', now()->year);
        $availableYears = array_keys($chartService->getYearlyTotals());
        if (empty($availableYears)) {
            $availableYears = [now()->year];
        }

        $layanans = Layanan::withCount('laporan')->get();
        $totalLaporan = LaporanPemesanan::count();

        $monthlyData = $chartService->getMonthlyTotals($year);
        $chartData = $chartService->formatChartData($monthlyData);

        $recentReports = LaporanPemesanan::with('layanan')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'layanans',
            'totalLaporan',
            'chartData',
            'year',
            'availableYears',
            'recentReports'
        ));
    }
}
