<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan {{ $layanan->nama_layanan }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2878E8; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #174A8B; font-size: 18px; }
        .header p { margin: 5px 0 0; color: #666; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; font-size: 11px; }
        th { background-color: #2878E8; color: white; font-weight: bold; text-align: center; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #888; border-top: 1px solid #ddd; padding-top: 10px; }
        .badge { padding: 3px 8px; border-radius: 12px; font-size: 10px; font-weight: bold; }
        .bg-success { background-color: #d1e7dd; color: #0f5132; }
        .bg-warning { background-color: #fff3cd; color: #664d03; }
        .bg-info { background-color: #cff4fc; color: #055160; }
        .bg-danger { background-color: #f8d7da; color: #842029; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN PEMESANAN {{ strtoupper($layanan->nama_layanan) }}</h2>
        <p>Dinas Perhubungan Kota Surakarta</p>
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 12%;">Kode</th>
                <th style="width: 15%;">Pemesan</th>
                <th style="width: 15%;">Instansi</th>
                <th style="width: 10%;">Tgl Pesan</th>
                <th style="width: 8%;">Jam</th>
                <th style="width: 20%;">Rute</th>
                <th style="width: 5%;">Jml</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item->kode_laporan }}</td>
                    <td>{{ $item->nama_pemesan }}</td>
                    <td>{{ $item->instansi }}</td>
                    <td style="text-align: center;">{{ $item->tanggal_pemesanan->format('d/m/Y') }}</td>
                    <td style="text-align: center;">{{ $item->jam_berangkat ? \Carbon\Carbon::parse($item->jam_berangkat)->format('H:i') : '-' }}</td>
                    <td>{{ $item->rute ?? '-' }}</td>
                    <td style="text-align: center;">{{ $item->jumlah_pemesanan }}</td>
                    <td style="text-align: center;">
                        <span class="badge
                            {{ $item->status == 'Disetujui' ? 'bg-success' : ($item->status == 'Ditolak' ? 'bg-danger' : ($item->status == 'Diproses' ? 'bg-info' : 'bg-warning')) }}">
                            {{ $item->status }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px;">Tidak ada data laporan untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh Sistem Monitoring Laporan Pemesanan Dishub Surakarta.</p>
    </div>

</body>
</html>
