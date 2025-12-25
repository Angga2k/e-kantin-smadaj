<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    protected $primaryKey = 'id_user';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $with = ['siswa', 'civitasAkademik', 'penjual'];
    protected $appends = ['foto_profile'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_user',
        'username',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Get the siswa record associated with the user.
     */
    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class, 'id_user', 'id_user');
    }

    /**
     * Get the civitas akademik record associated with the user.
     */
    public function civitasAkademik(): HasOne
    {
        return $this->hasOne(CivitasAkademik::class, 'id_user', 'id_user');
    }

    /**
     * Get the penjual record associated with the user.
     */
    public function penjual(): HasOne
    {
        return $this->hasOne(Penjual::class, 'id_user', 'id_user');
    }

    public function getFotoProfileAttribute()
    {
        $role = $this->role;
        $profile = null;

        // Ambil data profil berdasarkan role
        if ($role === 'siswa') {
            $profile = $this->siswa;
        } elseif ($role === 'civitas_akademik') {
            $profile = $this->civitasAkademik;
        } elseif ($role === 'penjual') {
            $profile = $this->penjual;
        }

        // 1. Cek jika foto profile asli ada
        if ($profile && !empty($profile->foto_profile)) {
            return $profile->foto_profile;
        }

        // 2. Jika tidak ada foto, cek jenis kelamin (untuk default gender)
        if ($profile && !empty($profile->jenis_kelamin)) {
            return 'icon/' .$profile->jenis_kelamin . '.png';
        }

        return null;
    }
}
