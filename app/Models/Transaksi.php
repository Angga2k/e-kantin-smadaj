<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_transaksi',
        'kode_transaksi',
        'external_id',
        'id_user_pembeli',
        'total_harga',
        'id_order_gateway',
        'payment_link',
        'metode_pembayaran',
        'status_pembayaran',
        'waktu_transaksi',
        'waktu_pengambilan',
        'detail_pengambilan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_harga' => 'decimal:2',
        'waktu_transaksi' => 'datetime',
        'waktu_pengambilan' => 'datetime',
    ];

    /**
     * Get the user that owns the transaksi.
     */
    public function pembeli(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user_pembeli', 'id_user');
    }

    /**
     * Get the detail transaksi for the transaksi.
     */
    public function detailTransaksi(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }
}
