<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;
use Exception;

class HistoryPenarikan extends Model
{
    use HasFactory;

    protected $table = 'history_penarikan';
    protected $primaryKey = 'id_penarikan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_penarikan',
        'id_dompet',
        'external_id',
        'jumlah',
        'bank_tujuan',
        'no_rekening',
        'status',
        'failure_code',
    ];

    protected static function boot()
    {
        parent::boot();
        // Otomatis buat UUID saat create
        static::creating(function ($model) {
            if (empty($model->id_penarikan)) {
                $model->id_penarikan = (string) Str::uuid();
            }
        });
    }

    /**
     * Mendapatkan Instance Encrypter Khusus
     * Menggunakan key dari .env 'DOMPET_KEY' (Sama seperti model Dompet)
     */
    protected function getDompetEncrypter()
    {
        // Ambil key mentah dari env
        $rawKey = env('DOMPET_KEY', 'COBA');

        // Hash key agar panjangnya 32 karakter (AES-256 requirement)
        $key = substr(hash('sha256', $rawKey), 0, 32);

        return new Encrypter($key, 'AES-256-CBC');
    }

    /**
     * Mutator: Set Jumlah (Encrypt saat disimpan ke DB)
     */
    public function setJumlahAttribute($value)
    {
        try {
            $encrypter = $this->getDompetEncrypter();
            $this->attributes['jumlah'] = $encrypter->encrypt($value);
        } catch (Exception $e) {
            throw new Exception("Gagal mengenkripsi jumlah penarikan: " . $e->getMessage());
        }
    }

    /**
     * Accessor: Get Jumlah (Decrypt saat diambil dari DB)
     */
    public function getJumlahAttribute($value)
    {
        try {
            $encrypter = $this->getDompetEncrypter();
            return (int) $encrypter->decrypt($value);
        } catch (Exception $e) {
            return 0; // Kembalikan 0 jika gagal dekripsi
        }
    }

    /**
     * Relasi ke Dompet
     */
    public function dompet()
    {
        return $this->belongsTo(Dompet::class, 'id_dompet', 'id_dompet');
    }
}
