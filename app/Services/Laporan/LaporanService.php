<?php

namespace App\Services\Laporan;

use App\Models\LaporanPemesanan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class LaporanService
{
    public function queryForLayanan(int $layananId, array $filters = []): Builder
    {
        $query = LaporanPemesanan::with(['layanan', 'user'])
            ->where('layanan_id', $layananId);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('nama_pemesan', 'like', "%{$search}%")
                  ->orWhere('instansi', 'like', "%{$search}%")
                  ->orWhere('kode_laporan', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['year'])) {
            $query->whereYear('tanggal_pemesanan', $filters['year']);
        }

        return $query->latest('tanggal_pemesanan');
    }

    public function paginate(Builder $query, int $perPage = 10): LengthAwarePaginator
    {
        return $query->paginate($perPage)->withQueryString();
    }

    public function getStatusCounts(int $layananId, ?int $year = null): array
    {
        $query = LaporanPemesanan::where('layanan_id', $layananId);

        if ($year) {
            $query->whereYear('tanggal_pemesanan', $year);
        }

        $counts = $query->selectRaw("status, COUNT(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return [
            'total' => array_sum($counts),
            'Ritel' => $counts['Ritel'] ?? 0,
            'Sewa' => $counts['Sewa'] ?? 0,
        ];
    }

    public function getAvailableYears(int $layananId): array
    {
        $years = LaporanPemesanan::where('layanan_id', $layananId)
            ->selectRaw('YEAR(tanggal_pemesanan) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        return $years ?: [now()->year];
    }
}

