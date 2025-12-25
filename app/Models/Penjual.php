<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjual extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'penjual';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'id_user',
        'nama_toko',
        'nama_penanggungjawab',
        'foto_profile',
        'no_rekening',
        'nama_bank',
    ];

    /**
     * Get the user that owns the penjual.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Get the barang for the penjual.
     */
    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'id_user_penjual', 'id_user');
    }
}