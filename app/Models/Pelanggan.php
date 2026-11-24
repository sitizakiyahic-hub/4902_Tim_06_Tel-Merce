<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pelanggan extends Model
{
    protected $table = 'daftar_pelanggan';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'no_hp',
        'member',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function alamatPengiriman(): HasMany
    {
        return $this->hasMany(AlamatPengiriman::class, 'pelanggan_id');
    }
}
