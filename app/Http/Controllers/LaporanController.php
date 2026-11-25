<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\StoreLaporanRequest;
use App\Models\LaporanPemesanan;
use App\Models\Layanan;
use Illuminate\Http\Request;


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
    public function exportPdf(string $slug)
    {
    $layanan = \App\Models\Layanan::bySlug($slug)->firstOrFail();

    $laporan = \App\Models\LaporanPemesanan::with(['layanan', 'user'])
        ->where('layanan_id', $layanan->id)
        ->latest('tanggal_pemesanan')
        ->get();

    $pdf = Pdf::loadView('laporan.pdf', compact('layanan', 'laporan'));
    $pdf->setPaper('A4', 'landscape');

    return $pdf->download('Laporan-' . $layanan->nama_layanan . '.pdf');
    }
}
