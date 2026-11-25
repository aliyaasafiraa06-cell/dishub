@extends('layouts.app')

@section('title', 'Tambah Laporan - ' . $layanan->nama_layanan)
@section('page_title', 'Tambah Laporan ' . $layanan->nama_layanan)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-plus-lg" style="font-size: 24px;"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Formulir Pemesanan Baru</h5>
                            <p class="text-muted small mb-0">Lengkapi data di bawah ini untuk layanan <strong>{{ $layanan->nama_layanan }}</strong>.</p>
                        </div>
                    </div>

                    <form action="{{ route('laporan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="layanan_id" value="{{ $layanan->id }}">
                        <input type="hidden" name="layanan_slug" id="layanan_slug" value="{{ $layanan->slug }}">

                        {{-- Informasi Pemesan --}}
                        <h6 class="fw-bold text-primary mb-3 mt-2"><i class="bi bi-person-badge"></i> Informasi Pemesan</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Nama Pemesan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pemesan" class="form-control @error('nama_pemesan') is-invalid @enderror" value="{{ old('nama_pemesan') }}" required>
                                @error('nama_pemesan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Instansi <span class="text-danger">*</span></label>
                                <input type="text" name="instansi" class="form-control @error('instansi') is-invalid @enderror" value="{{ old('instansi') }}" required>
                                @error('instansi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">No. Telepon</label>
                                <input type="text" name="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" value="{{ old('no_telepon') }}" placeholder="08xxxxxxxxxx">
                                @error('no_telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Jumlah Pemesanan (Orang) <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah_pemesanan" class="form-control @error('jumlah_pemesanan') is-invalid @enderror" value="{{ old('jumlah_pemesanan', 1) }}" min="1" required>
                                @error('jumlah_pemesanan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Jadwal & Rute --}}
                        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-calendar-event"></i> Jadwal & Rute Perjalanan</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Tanggal Pemesanan <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_pemesanan" class="form-control @error('tanggal_pemesanan') is-invalid @enderror" value="{{ old('tanggal_pemesanan', date('Y-m-d')) }}" required>
                                @error('tanggal_pemesanan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Tanggal Pelaksanaan</label>
                                <input type="date" name="tanggal_pelaksanaan" class="form-control @error('tanggal_pelaksanaan') is-invalid @enderror" value="{{ old('tanggal_pelaksanaan') }}">
                                @error('tanggal_pelaksanaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Jam Berangkat</label>
                                <input type="time" name="jam_berangkat" class="form-control @error('jam_berangkat') is-invalid @enderror" value="{{ old('jam_berangkat') }}">
                                @error('jam_berangkat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                               {{-- <!-- DEBUG: Slug saat ini adalah: {{ $layanan->slug }} --> --}}
                                <div class="alert alert-info py-1 px-2 mb-2 small">
                                    <i class="bi bi-info-circle"></i> Sistem membaca slug layanan sebagai: <strong>{{ $layanan->slug }}</strong>
                                </div>
                                   <label class="form-label fw-semibold small">Pilih Rute <span class="text-danger">*</span></label>
                                <select name="rute" id="select_rute" class="form-select @error('rute') is-invalid @enderror" required>
                                    <option value="">-- Pilih Rute --</option>
                                    @foreach (\App\Models\LaporanPemesanan::getRuteBySlug($layanan->slug) as $rute)
                                        <option value="{{ $rute }}" {{ old('rute') == $rute ? 'selected' : '' }}>{{ $rute }}</option>
                                    @endforeach
                                </select>
                                @error('rute') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <small class="text-muted">Rute akan menyesuaikan dengan layanan yang dipilih.</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Titik Pemberhentian</label>
                                <input type="text" name="titik_pemberhentian" class="form-control @error('titik_pemberhentian') is-invalid @enderror" value="{{ old('titik_pemberhentian') }}" placeholder="Contoh: Halte Mangkunegaran">
                                @error('titik_pemberhentian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Detail Pemesanan --}}
                        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-card-checklist"></i> Detail Pemesanan</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Keperluan <span class="text-danger">*</span></label>
                                <textarea name="keperluan" rows="3" class="form-control @error('keperluan') is-invalid @enderror" required>{{ old('keperluan') }}</textarea>
                                @error('keperluan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    @foreach (\App\Models\LaporanPemesanan::STATUS_OPTIONS as $status)
                                        <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small">Keterangan Tambahan</label>
                                <textarea name="keterangan" rows="2" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
                                @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('layanan.show', $layanan->slug) }}" class="btn btn-outline-secondary" style="border-radius: 10px;">
                                <i class="bi bi-x-lg"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">
                                <i class="bi bi-save"></i> Simpan Laporan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
