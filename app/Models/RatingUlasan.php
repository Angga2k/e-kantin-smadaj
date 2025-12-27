<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RatingUlasan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'rating_ulasan';
    protected $primaryKey = 'id_rating';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_rating',
        'id_barang',
        'id_user_siswa',
        'id_detail_transaksi',
        'rating',
        'ulasan',
    ];

    /**
     * Get the barang that owns the rating ulasan.
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    /**
     * Get the siswa that owns the rating ulasan.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user_siswa', 'id_user');
    }

    /**
     * Get the detail transaksi that owns the rating ulasan.
     */
    public function detailTransaksi(): BelongsTo
    {
        return $this->belongsTo(DetailTransaksi::class, 'id_detail_transaksi', 'id_detail');
    }
}