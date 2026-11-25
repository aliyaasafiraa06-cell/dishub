<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExport;
use App\Http\Requests\StoreLaporanRequest;
use App\Models\LaporanPemesanan;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    // Tampilkan form tambah laporan
    public function create(string $layananSlug)
    {
        $layanan = Layanan::bySlug($layananSlug)->firstOrFail();
        return view('laporan.create', compact('layanan'));
    }

    // Simpan laporan baru ke database
    public function store(StoreLaporanRequest $request)
    {
        $data = $request->validated();
        $data['kode_laporan'] = LaporanPemesanan::generateKode();
        $data['user_id'] = auth()->id();

        LaporanPemesanan::create($data);

        return redirect()
            ->route('layanan.show', Layanan::find($data['layanan_id'])->slug)
            ->with('success', 'Laporan berhasil ditambahkan.');
    }

    // Tampilkan detail laporan
    public function show(LaporanPemesanan $laporan)
    {
        $laporan->load(['layanan', 'user']);
        return view('laporan.show', compact('laporan'));
    }

    // Tampilkan form edit laporan
    public function edit(LaporanPemesanan $laporan)
    {
        $laporan->load('layanan');
        return view('laporan.edit', compact('laporan'));
    }

    // Update laporan ke database
    public function update(StoreLaporanRequest $request, LaporanPemesanan $laporan)
    {
        $laporan->update($request->validated());

        return redirect()
            ->route('laporan.show', $laporan)
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    // Hapus laporan dari database
    public function destroy(LaporanPemesanan $laporan)
    {
        $slug = $laporan->layanan->slug;
        $laporan->delete();

        return redirect()
            ->route('layanan.show', $slug)
            ->with('success', 'Laporan berhasil dihapus.');
    }

    // Ekspor Excel per layanan (mengikuti filter yang aktif)
    public function exportExcel(Request $request, string $slug)
    {
        $layanan = Layanan::bySlug($slug)->firstOrFail();

        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'year'   => $request->get('year'),
        ];

        $namaFile = 'Laporan-' . $layanan->slug . '-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(new LaporanExport($layanan, $filters), $namaFile);
    }
}
