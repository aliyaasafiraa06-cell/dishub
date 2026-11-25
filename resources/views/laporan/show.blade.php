@extends('layouts.app')

@section('title', 'Detail Laporan - ' . $laporan->kode_laporan)
@section('page_title', 'Detail Laporan')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        <div>
                            <h5 class="fw-bold mb-1">Detail Laporan</h5>
                            <p class="text-muted small mb-0">Kode: <code class="bg-light px-2 py-1 rounded">{{ $laporan->kode_laporan }}</code></p>
                        </div>

                        {{-- Tombol Edit & Hapus (Hanya untuk Admin) --}}
                        @if(auth()->user()->role === 'admin')
                        <div class="d-flex gap-2">
                            <a href="{{ route('laporan.edit', $laporan) }}" class="btn btn-warning text-white" style="border-radius: 10px;">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('laporan.destroy', $laporan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="border-radius: 10px;">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted fw-semibold text-uppercase" style="font-size: 11px;">Layanan</small>
                                <div class="fw-bold fs-5 text-primary">{{ $laporan->layanan->nama_layanan }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted fw-semibold text-uppercase" style="font-size: 11px;">Status</small>
                                <div class="mt-1">
                                    @php
                                        $badgeClass = [
                                            'Ritel' => 'bg-primary text-white',
                                            'Sewa' => 'bg-success text-white',
                                        ];
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-3 py-2" style="font-size: 14px; border-radius: 20px;">
                                        {{ $laporan->status }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted fw-semibold text-uppercase" style="font-size: 11px;">Nama Pemesan</small>
                                <div class="fw-bold">{{ $laporan->nama_pemesan }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted fw-semibold text-uppercase" style="font-size: 11px;">Instansi</small>
                                <div class="fw-bold">{{ $laporan->instansi }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted fw-semibold text-uppercase" style="font-size: 11px;">No. Telepon</small>
                                <div class="fw-bold">{{ $laporan->no_telepon ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted fw-semibold text-uppercase" style="font-size: 11px;">Jumlah Pemesanan</small>
                                <div class="fw-bold">{{ $laporan->jumlah_pemesanan }} Orang</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted fw-semibold text-uppercase" style="font-size: 11px;">Tanggal Pemesanan</small>
                                <div class="fw-bold">{{ $laporan->tanggal_pemesanan->format('d M Y') }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted fw-semibold text-uppercase" style="font-size: 11px;">Tanggal Pelaksanaan</small>
                                <div class="fw-bold">{{ $laporan->tanggal_pelaksanaan ? $laporan->tanggal_pelaksanaan->format('d M Y') : '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted fw-semibold text-uppercase" style="font-size: 11px;">Jam Berangkat</small>
                                <div class="fw-bold">{{ $laporan->jam_berangkat ? \Carbon\Carbon::parse($laporan->jam_berangkat)->format('H:i') : '-' }}</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted fw-semibold text-uppercase" style="font-size: 11px;">Rute</small>
                                <div class="fw-bold">{{ $laporan->rute ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted fw-semibold text-uppercase" style="font-size: 11px;">Titik Pemberhentian</small>
                                <div class="fw-bold">{{ $laporan->titik_pemberhentian ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted fw-semibold text-uppercase" style="font-size: 11px;">Keperluan</small>
                                <div class="fw-bold">{{ $laporan->keperluan }}</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted fw-semibold text-uppercase" style="font-size: 11px;">Keterangan Tambahan</small>
                                <div class="fw-bold">{{ $laporan->keterangan ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted fw-semibold text-uppercase" style="font-size: 11px;">Dibuat Oleh</small>
                                <div class="fw-bold">{{ $laporan->user->name ?? 'Sistem' }} ({{ ucfirst($laporan->user->role ?? 'admin') }})</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <a href="{{ route('layanan.show', $laporan->layanan->slug) }}" class="btn btn-outline-secondary" style="border-radius: 10px;">
                            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Laporan
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
