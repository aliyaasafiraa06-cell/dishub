<?php

namespace App\Http\Requests;

use App\Models\LaporanPemesanan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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
            'jam_berangkat' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $jam = $this->input('jam_berangkat');
        if ($jam) {
            // Normalisasi: ganti titik dengan titik dua
            $jam = str_replace('.', ':', $jam);

            // Validasi format HH:MM
            if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $jam)) {
                $validator->errors()->add('jam_berangkat', 'Format jam tidak valid. Gunakan format HH:MM (contoh: 08:30 atau 11:30).');
            }

            // Simpan format yang sudah dinormalisasi
            $this->merge(['jam_berangkat_normalized' => $jam]);
        }
    });
}
}
