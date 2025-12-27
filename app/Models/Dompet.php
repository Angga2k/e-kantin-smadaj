<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;
use Exception;

class Dompet extends Model
{
    use HasFactory;

    protected $table = 'dompet';
    protected $primaryKey = 'id_dompet';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_dompet',
        'id_user',
        'saldo',
    ];

    protected static function boot()
    {
        parent::boot();
        // Otomatis buat UUID saat create
        static::creating(function ($model) {
            if (empty($model->id_dompet)) {
                $model->id_dompet = (string) Str::uuid();
            }
            // Set saldo awal 0 jika belum diisi
            if (!isset($model->attributes['saldo'])) {
                $model->saldo = 0;
            }
        });
    }

    /**
     * Mendapatkan Instance Encrypter Khusus
     * Menggunakan key dari .env 'DOMPET_KEY'
     */
    protected function getDompetEncrypter()
    {
        // Ambil key mentah dari env
        $rawKey = env('DOMPET_KEY', 'COBA');

        // Laravel AES-256 butuh key tepat 32 karakter.
        // Kita hash key dari env (misal 'COBA') menjadi SHA-256 (32 bytes/64 hex)
        // lalu ambil 32 karakter pertama agar valid.
        $key = substr(hash('sha256', $rawKey), 0, 32);

        return new Encrypter($key, 'AES-256-CBC');
    }

    /**
     * Mutator: Set Saldo (Encrypt saat disimpan ke DB)
     * Dipanggil saat: $dompet->saldo = 50000;
     */
    public function setSaldoAttribute($value)
    {
        try {
            $encrypter = $this->getDompetEncrypter();
            // Enkripsi nilai nominal
            $this->attributes['saldo'] = $encrypter->encrypt($value);
        } catch (Exception $e) {
            // Fallback jika gagal (sebaiknya di log)
            throw new Exception("Gagal mengenkripsi saldo dompet: " . $e->getMessage());
        }
    }

    /**
     * Accessor: Get Saldo (Decrypt saat diambil dari DB)
     * Dipanggil saat: echo $dompet->saldo;
     */
    public function getSaldoAttribute($value)
    {
        try {
            $encrypter = $this->getDompetEncrypter();
            // Dekripsi nilai
            return (int) $encrypter->decrypt($value);
        } catch (Exception $e) {
            // Jika key berubah atau data rusak, kembalikan 0 atau error
            return 0;
        }
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
