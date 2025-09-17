<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['delete_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_barang',
        'id_user_penjual',
        'nama_barang',
        'deskripsi_barang',
        'jenis_barang',
        'harga',
        'stok',
        'foto_barang',
        'kalori_kkal',
        'protein_g',
        'lemak_g',
        'karbohidrat_g',
        'serat_g',
        'gula_g',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'harga' => 'decimal:2',
        'protein_g' => 'decimal:2',
        'lemak_g' => 'decimal:2',
        'karbohidrat_g' => 'decimal:2',
        'serat_g' => 'decimal:2',
        'gula_g' => 'decimal:2',
        'delete_at' => 'datetime',
    ];

    /**
     * Get the penjual that owns the barang.
     */
    public function penjual(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user_penjual', 'id_user');
    }

    /**
     * Get the detail transaksi for the barang.
     */
    public function detailTransaksi(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'id_barang', 'id_barang');
    }

    /**
     * Get the rating ulasan for the barang.
     */
    public function ratingUlasan(): HasMany
    {
        return $this->hasMany(RatingUlasan::class, 'id_barang', 'id_barang');
    }
}