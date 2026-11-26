
@extends('layouts.app')

@section('title', 'Laporan ' . $layanan->nama_layanan)

@section('page_title', 'Laporan ' . $layanan->nama_layanan)

@section('content')

<div class="container-fluid">

    {{-- Header Halaman --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                Laporan {{ $layanan->nama_layanan }}
            </h4>
            <p class="text-muted mb-0 small">
                {{ $layanan->deskripsi }}
            </p>
        </div>

        @if(auth()->user()->role === 'admin')
            <div class="d-flex gap-2">
                <a href="{{ route('laporan.create', $layanan->slug) }}"
                   class="btn btn-primary d-flex align-items-center gap-2"
                   style="border-radius: 10px;">
                    <i class="bi bi-plus-lg"></i> Tambah Laporan
                </a>

                <a href="{{ route('laporan.export.excel', [$layanan->slug, 'search' => request('search'), 'status' => request('status'), 'year' => $year]) }}"
                    class="btn btn-outline-success d-flex align-items-center gap-2"
                    style="border-radius: 10px;">
                        <i class="bi bi-file-earmark-excel"></i> Ekspor Excel
                </a>
            </div>
        @else
            <div class="alert alert-info mb-0 py-2 px-3"
                 style="border-radius: 10px; font-size: 13px;">
                <i class="bi bi-info-circle"></i>
                Mode Lihat Saja (Hubungi admin untuk perubahan data)
            </div>
        @endif
    </div>

    {{-- Kartu Ringkasan Status --}}
    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-md-4 col-12">
            <div class="card border-0 shadow-sm h-100"
                 style="border-radius: 16px;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center"
                         style="width: 48px; height: 48px;">
                        <i class="bi bi-files" style="font-size: 20px;"></i>
                    </div>

                    <div>
                        <div class="text-muted small fw-semibold mb-1">
                            Total Laporan
                        </div>
                        <div class="fs-4 fw-bold text-dark mb-0">
                            {{ $statusCounts['total'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ritel --}}
        <div class="col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100"
                 style="border-radius: 16px;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center"
                         style="width: 48px; height: 48px;">
                        <i class="bi bi-cart3" style="font-size: 20px;"></i>
                    </div>

                    <div>
                        <div class="text-muted small fw-semibold mb-1">
                            Ritel
                        </div>
                        <div class="fs-4 fw-bold text-dark mb-0">
                            {{ $statusCounts['Ritel'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sewa --}}
        <div class="col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100"
                 style="border-radius: 16px;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center"
                         style="width: 48px; height: 48px;">
                        <i class="bi bi-calendar-check" style="font-size: 20px;"></i>
                    </div>

                    <div>
                        <div class="text-muted small fw-semibold mb-1">
                            Sewa
                        </div>
                        <div class="fs-4 fw-bold text-dark mb-0">
                            {{ $statusCounts['Sewa'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Grafik Bulanan --}}
    <div class="card border-0 shadow-sm mb-4"
         style="border-radius: 16px;">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold mb-1">Grafik Laporan Bulanan</h6>
                    <small class="text-muted">
                        Khusus layanan {{ $layanan->nama_layanan }}
                    </small>
                </div>

                <form method="GET" class="d-flex gap-2">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif

                    <select name="year"
                            class="form-select form-select-sm"
                            style="width: 110px; border-radius: 10px;">

                        @foreach ($availableYears as $y)
                            <option value="{{ $y }}"
                                {{ $y == $year ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach

                    </select>

                    <button type="submit"
                            class="btn btn-primary btn-sm"
                            style="border-radius: 10px;">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </form>
            </div>

            <canvas id="layananChart" height="80"></canvas>

        </div>
    </div>

    {{-- Tabel Laporan --}}
    <div class="card border-0 shadow-sm"
         style="border-radius: 16px;">
        <div class="card-body p-4">

            {{-- Toolbar Pencarian & Filter --}}
            <form method="GET"
                  class="row g-2 mb-4 align-items-end">

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted mb-1">
                        Pencarian
                    </label>

                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control"
                           placeholder="Cari nama, instansi, atau kode..."
                           style="border-radius: 10px;">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted mb-1">
                        Status
                    </label>

                    <select name="status"
                            class="form-select"
                            style="border-radius: 10px;">

                        <option value="">Semua Status</option>

                        @foreach (\App\Models\LaporanPemesanan::STATUS_OPTIONS as $s)
                            <option value="{{ $s }}"
                                {{ request('status') == $s ? 'selected' : '' }}>
                                {{ $s }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- Pertahankan filter tahun ketika mencari --}}
                <input type="hidden" name="year" value="{{ request('year', $year) }}">

                <div class="col-md-2">
                    <button type="submit"
                            class="btn btn-primary w-100"
                            style="border-radius: 10px;">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>

                <div class="col-md-3">
                    <a href="{{ route('layanan.show', $layanan->slug) }}"
                       class="btn btn-outline-secondary w-100"
                       style="border-radius: 10px;">
                        <i class="bi bi-x-lg"></i> Reset Filter
                    </a>
                </div>

            </form>

            {{-- Isi Tabel --}}
            @if ($laporan->isEmpty())

                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size: 48px;"></i>
                    <p class="mt-2 mb-0">
                        Belum ada laporan untuk filter ini.
                    </p>
                </div>

            @else

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px;">No</th>
                                <th>Kode</th>
                                <th>Pemesan</th>
                                <th>Instansi</th>
                                <th>Tgl Pesan</th>
                                <th>Jam Berangkat</th>
                                <th>Rute</th>
                                <th>Supir</th>
                                <th>Jml</th>
                                <th>Status</th>

                                @if(auth()->user()->role === 'admin')
                                    <th style="width: 140px;">Aksi</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($laporan as $index => $item)

                                <tr>
                                    <td>{{ $laporan->firstItem() + $index }}</td>

                                    <td>
                                        <code>{{ $item->kode_laporan }}</code>
                                    </td>

                                    <td>{{ $item->nama_pemesan }}</td>

                                    <td>{{ $item->instansi }}</td>

                                    <td>
                                        {{ $item->tanggal_pemesanan->format('d M Y') }}
                                    </td>

                                    <td>
                                        {{ $item->jam_berangkat
                                            ? \Carbon\Carbon::parse($item->jam_berangkat)->format('H:i')
                                            : '-' }}
                                    </td>

                                    <td>
                                        <small class="text-truncate d-block"
                                               style="max-width: 200px;"
                                               title="{{ $item->rute }}">
                                            {{ $item->rute ?? '-' }}
                                        </small>
                                    </td>
                                    <td>{{ $item->nama_supir ?? '-' }}</td>

                                    <td>{{ $item->jumlah_pemesanan }}</td>

                                    {{-- Badge Status --}}
                                    <td>
                                        @php
                                            $badgeClass = [
                                                'Ritel' => 'bg-primary bg-opacity-10 text-primary',
                                                'Sewa' => 'bg-success bg-opacity-10 text-success',
                                            ];
                                        @endphp

                                        <span class="badge {{ $badgeClass[$item->status] ?? 'bg-secondary' }} px-3 py-2"
                                              style="border-radius: 20px;">
                                            {{ $item->status }}
                                        </span>
                                    </td>

                                    {{-- Kolom Aksi (Hanya Admin) --}}
                                    @if(auth()->user()->role === 'admin')

                                        <td>
                                            <div class="d-flex gap-1">

                                                <a href="{{ route('laporan.show', $item) }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                <a href="{{ route('laporan.edit', $item) }}"
                                                   class="btn btn-sm btn-outline-warning"
                                                   title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                <form action="{{ route('laporan.destroy', $item) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>

                                                </form>

                                            </div>
                                        </td>

                                    @endif

                                </tr>

                            @endforeach
                        </tbody>

                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <small class="text-muted">
                        Menampilkan {{ $laporan->firstItem() }}-{{ $laporan->lastItem() }}
                        dari {{ $laporan->total() }} data
                    </small>

                    {{ $laporan->links() }}

                </div>

            @endif

        </div>
    </div>

</div>

@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
    const ctx = document.getElementById('layananChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',

        data: {
            labels: @json($chartData['labels']),

            datasets: [{
                label: 'Jumlah Laporan',

                data: @json($chartData['values']),

                borderColor: '#2878E8',

                backgroundColor: 'rgba(40,120,232,0.1)',

                fill: true,

                tension: 0.35,

                pointBackgroundColor: '#2878E8',

                pointRadius: 4,
            }]
        },

        options: {
            responsive: true,

            plugins: {
                legend: {
                    display: false
                }
            },

            scales: {
                y: {
                    beginAtZero: true,

                    ticks: {
                        precision: 0
                    },

                    grid: {
                        color: '#E7ECF3'
                    }
                },

                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>

@endpush
