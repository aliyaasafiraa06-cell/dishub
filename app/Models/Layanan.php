<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layanan extends Model
{
    protected $table = 'layanans';

    protected $fillable = [
        'nama_layanan',
        'slug',
        'icon',
        'deskripsi',
    ];

    public function laporan(): HasMany
    {
        return $this->hasMany(LaporanPemesanan::class, 'layanan_id');
    }

    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }
}
