<?php

namespace App\Models;

use Database\Factories\ProdukFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produk extends Model
{
    /** @use HasFactory<ProdukFactory> */
    use HasFactory;

    protected $table = 'produk';

    public $timestamps = false;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'sku',
        'merek',
        'kategori',
        'berat',
        'warna',
        'ukuran',
        'stok',
        'harga',
        'foto',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_locked' => false,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'berat' => 'integer',
            'stok' => 'integer',
            'harga' => 'integer',
            'is_locked' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function pemilik(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
