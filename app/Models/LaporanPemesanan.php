<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanPemesanan extends Model
{
    protected $table = 'laporan_pemesanans';

    protected $fillable = [
        'kode_laporan',
        'layanan_id',
        'user_id',
        'nama_pemesan',
        'instansi',
        'no_telepon',
        'tanggal_pemesanan',
        'tanggal_pelaksanaan',
        'jam_berangkat',
        'rute',
        'titik_pemberhentian',
        'nama_supir',          // <-- baru
        'jumlah_pemesanan',
        'keperluan',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pemesanan' => 'date',
        'tanggal_pelaksanaan' => 'date',
        'jumlah_pemesanan' => 'integer',
    ];

    public const STATUS_OPTIONS = [
        'Ritel',
        'Sewa',
    ];

    // Data Rute untuk Setiap Layanan
    public const RUTE_OPTIONS = [
        'angkutan-listrik' => [
            'Rute 1: Benteng Vastenburg - Keraton Kasunanan - Klewer(melintas) - KP. Batik Kauman - Mangkunegaran - Benteng',
            'Rute 2: Pasar Oleh-Oleh Jongke - Kel. Sondakan - KP. Batik Laweyan - Bratan - Pasar Oleh-Oleh Jongke ',
            'Rute 3: Kantor Dishub Manahan - Mangkunegaran - Monumen Pers - Depok - Dishub',
        ],
        'bus-tingkat' => [
            'Rute: Dishub Manahan - Overpass Manahan - Gendengan - Gladag - Balaikota - Panggung - Cembengan - Jurug - Cembengan - Pangging - Jl. Monginsidi - Ap. 5 Balapan - SP. 3 Gilingan - Terminal Tirtonadi - SP3 Kadin - Dishub',
        ],
        'bus-edukasi' => [
            'Rute Klaten: Dishub - Sentra Soun - Balai Benih Ikan - Umbul Pelem',
            'Rute Boyolali: Dishub - Indrokilo - Umbul Sungsang - Bekonang - Sentra Karak/Rambak - Bapenda Kemanten',
        ],
        'kereta-jaladara' => [
            'Rute: Sta. Purwosari - Sta. Kota - Sta. Purwosari',
        ],
    ];

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateKode(): string
    {
        $prefix = 'LP';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 5));

        return "{$prefix}-{$date}-{$random}";
    }

    // Helper untuk mendapatkan rute berdasarkan slug layanan
    public static function getRuteBySlug(string $slug): array
    {
        return self::RUTE_OPTIONS[$slug] ?? [];
    }
}
