<?php

namespace App\Http\Requests;

use App\Models\LaporanPemesanan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // akses admin sudah dijaga middleware isAdmin di route
    }

    /**
     * Normalisasi SEBELUM validasi: "08.30" -> "08:30", "8:30" -> "08:30".
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('jam_berangkat')) {
            $jam = str_replace('.', ':', trim((string) $this->input('jam_berangkat')));

            if (preg_match('/^(\d{1,2}):(\d{2})$/', $jam, $m)) {
                $jam = sprintf('%02d:%02d', (int) $m[1], (int) $m[2]);
            }

            $this->merge(['jam_berangkat' => $jam]);
        }
    }

    public function rules(): array
    {
        return [
            'layanan_id' => ['required', 'exists:layanans,id'],
            'nama_pemesan' => ['required', 'string', 'max:255'],
            'instansi' => ['required', 'string', 'max:255'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'tanggal_pemesanan' => ['required', 'date'],
            'tanggal_pelaksanaan' => ['nullable', 'date', 'after_or_equal:tanggal_pemesanan'],
            'jam_berangkat' => ['nullable', 'date_format:H:i'],
            'rute' => ['nullable', 'string', 'max:500'],
            'titik_pemberhentian' => ['nullable', 'string', 'max:500'],
            'nama_supir' => ['nullable', 'string', 'max:255'],
            'jumlah_pemesanan' => ['required', 'integer', 'min:1'],
            'keperluan' => ['required', 'string'],
            'status' => ['required', Rule::in(LaporanPemesanan::STATUS_OPTIONS)],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_pelaksanaan.after_or_equal' => 'Tanggal pelaksanaan tidak boleh lebih awal dari tanggal pemesanan.',
            'jumlah_pemesanan.min' => 'Jumlah pemesanan minimal 1.',
            'jam_berangkat.date_format' => 'Format jam tidak valid. Gunakan format HH:MM (contoh: 08:30).',
            'status.in' => 'Status harus salah satu dari: ' . implode(', ', LaporanPemesanan::STATUS_OPTIONS) . '.',
        ];
    }
}