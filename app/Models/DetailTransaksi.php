<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi';
    protected $primaryKey = 'id_detail';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_transaksi',
        'id_barang',
        'jumlah',
        'harga_saat_transaksi',
        'status_barang',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'harga_saat_transaksi' => 'decimal:2',
    ];

    /**
     * Get the transaksi that owns the detail transaksi.
     */
    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    /**
     * Get the barang that owns the detail transaksi.
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    /**
     * Get the rating ulasan associated with the detail transaksi.
     */
    public function ratingUlasan(): HasOne
    {
        return $this->hasOne(RatingUlasan::class, 'id_detail_transaksi', 'id_detail');
    }
}