@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
    <!-- Welcome Panel -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: linear-gradient(135deg, #2878E8 0%, #174A8B 100%); color: white;">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="fw-bold mb-1">Selamat datang, {{ Auth::user()->name }}</h3>
                    <p class="mb-0 opacity-75">Pantau laporan seluruh layanan transportasi Dishub Kota Surakarta dalam satu dashboard.</p>
                </div>
                <div class="col-md-4 text-end d-none d-md-block">
                    <i class="bi bi-bus-front-fill" style="font-size: 60px; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-clipboard-data" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold mb-1">Total Seluruh Laporan</div>
                        <div class="fs-3 fw-bold text-primary mb-0">{{ number_format($totalLaporan) }}</div>
                    </div>
                </div>
            </div>
        </div>

        @php
            $iconMap = [
                'angkutan-listrik' => ['bi-ev-front', 'text-info'],
                'bus-tingkat' => ['bi-bus-front', 'text-success'],
                'bus-edukasi' => ['bi-mortarboard', 'text-warning'],
                'kereta-jaladara' => ['bi-train-front', 'text-danger'],
            ];
        @endphp

        @foreach ($layanans as $layanan)
            @php
                [$icon, $color] = $iconMap[$layanan->slug] ?? ['bi-folder', 'text-primary'];
            @endphp
            <div class="col-md-6 col-xl">
                <a href="#" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                                <i class="bi {{ $icon }} {{ $color }}" style="font-size: 24px;"></i>
                            </div>
                            <div>
                                <div class="text-muted small fw-semibold mb-1">{{ $layanan->nama_layanan }}</div>
                                <div class="fs-3 fw-bold text-dark mb-0">{{ number_format($layanan->laporan_count) }}</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <!-- Chart -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold mb-1">Total Laporan Bulanan</h6>
                    <small class="text-muted">Jumlah laporan pemesanan dari seluruh layanan</small>
                </div>
                <form method="GET" class="d-flex gap-2">
                    <select name="year" class="form-select form-select-sm" style="width: 110px;">
                        @foreach ($availableYears as $y)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </form>
            </div>
            <canvas id="monthlyChart" height="90"></canvas>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Laporan Terbaru</h6>
                <a href="#" class="text-decoration-none small">Lihat semua →</a>
            </div>
            @if ($recentReports->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size: 48px;"></i>
                    <p class="mt-2 mb-0">Belum ada laporan. Klik "Tambah Laporan" untuk membuat yang pertama.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Pemesan</th>
                                <th>Layanan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentReports as $report)
                                <tr>
                                    <td><code>{{ $report->kode_laporan }}</code></td>
                                    <td>{{ $report->nama_pemesan }}</td>
                                    <td>{{ $report->layanan->nama_layanan }}</td>
                                    <td>{{ $report->tanggal_pemesanan->format('d M Y') }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'Ritel' => 'primary',
                                                'Sewa' => 'success',
                                            ];

                                            $badgeColor = $statusColors[$report->status] ?? 'secondary';
                                        @endphp

                                        <span class="badge bg-{{ $badgeColor }} bg-opacity-10 text-{{ $badgeColor }} px-3 py-2">
                                            {{ $report->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Jumlah Laporan',
                data: @json($chartData['values']),
                backgroundColor: '#2878E8',
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#172B4D',
                    padding: 12,
                    cornerRadius: 8,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    grid: { color: '#E7ECF3' }
                },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
