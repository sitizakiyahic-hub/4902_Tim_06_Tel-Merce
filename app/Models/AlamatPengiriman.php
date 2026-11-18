<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlamatPengiriman extends Model
{
    protected $table = 'alamat_pengiriman';

    public $timestamps = false;

    protected $fillable = [
        'pelanggan_id',
        'label',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'status',
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }
}
