<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RekeningTujuan extends Model
{
    use HasFactory;

    protected $table = 'rekening_tujuan';
    protected $primaryKey = 'id_rekening';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_rekening',
        'id_user',
        'nama_bank',
        'no_rekening',
        'atas_nama',
    ];

    /**
     * Casting Otomatis Laravel
     * 'encrypted' menggunakan APP_KEY di .env
     * Otomatis mengenkripsi saat save, dan mendekripsi saat diakses
     */
    protected $casts = [
        'no_rekening' => 'encrypted',
    ];

    protected static function boot()
    {
        parent::boot();
        // Otomatis generate UUID saat create data baru
        static::creating(function ($model) {
            if (empty($model->id_rekening)) {
                $model->id_rekening = (string) Str::uuid();
            }
        });
    }

    /**
     * Accessor untuk tampilan Masking (Sensor Angka)
     * Panggil di view dengan: $rek->masked
     * Contoh Output: ******0819
     */
    public function getMaskedAttribute()
    {
        // $this->no_rekening otomatis sudah didekripsi oleh casts
        $noRek = $this->no_rekening;
        $length = strlen($noRek);

        // Jika kurang dari 4 digit, tampilkan saja semua
        if ($length <= 4) {
            return $noRek;
        }

        // Ganti semua digit awal dengan '*', sisakan 4 terakhir
        return str_repeat('*', $length - 4) . substr($noRek, -4);
    }

    /**
     * Relasi ke User pemilik rekening
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
