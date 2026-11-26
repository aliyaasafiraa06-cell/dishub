<?php

namespace App\Exports;

use App\Models\Layanan;
use App\Services\Laporan\LaporanService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{

    private int $no = 0;

    public function __construct(
        private Layanan $layanan,
        private array $filters = []
    ) {}

    /**
     * Ambil data dengan query yang SAMA dengan tabel di halaman layanan,
     * sehingga hasil ekspor sesuai filter (tahun, status, pencarian).
     */
    public function collection(): Collection
    {

        return app(LaporanService::class)
            ->queryForLayanan($this->layanan->id, $this->filters)
            ->get();
    }

    public function title(): string
    {
        return $this->layanan->nama_layanan;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Laporan',
            'Nama Pemesan',
            'Instansi',
            'No. Telepon',
            'Tanggal Pemesanan',
            'Tanggal Pelaksanaan',
            'Jam Berangkat',
            'Rute',
            'Titik Pemberhentian',
            'Nama Supir',          // <-- kolom baru
            'Jumlah Pemesanan',
            'Keperluan',
            'Status',
            'Keterangan',
        ];
    }

    public function map($laporan): array
    {
        return [
            ++$this->no,
            $laporan->kode_laporan,
            $laporan->nama_pemesan,
            $laporan->instansi,
            $laporan->no_telepon ?? '-',
            $laporan->tanggal_pemesanan->format('d/m/Y'),
            $laporan->tanggal_pelaksanaan?->format('d/m/Y') ?? '-',
            $laporan->jam_berangkat ? Carbon::parse($laporan->jam_berangkat)->format('H:i') : '-',
            $laporan->rute ?? '-',
            $laporan->titik_pemberhentian ?? '-',
            $laporan->nama_supir ?? '-',   // <-- harus sejajar dengan heading di atas
            $laporan->jumlah_pemesanan,
            $laporan->keperluan,
            $laporan->status,
            $laporan->keterangan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Baris header dicetak tebal
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
