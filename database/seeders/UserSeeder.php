<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Admin Users
            [
                'id_user' => Str::uuid(),
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ],
            [
                'id_user' => Str::uuid(),
                'username' => 'superadmin',
                'password' => Hash::make('super123'),
                'role' => 'admin',
            ],
            
            // Siswa Users (NISN as username)
            [
                'id_user' => Str::uuid(),
                'username' => '2021001001',
                'password' => Hash::make('siswa123'),
                'role' => 'siswa',
            ],
            [
                'id_user' => Str::uuid(),
                'username' => '2021001002',
                'password' => Hash::make('siswa123'),
                'role' => 'siswa',
            ],
            [
                'id_user' => Str::uuid(),
                'username' => '2021001003',
                'password' => Hash::make('siswa123'),
                'role' => 'siswa',
            ],
            [
                'id_user' => Str::uuid(),
                'username' => '2021001004',
                'password' => Hash::make('siswa123'),
                'role' => 'siswa',
            ],
            [
                'id_user' => Str::uuid(),
                'username' => '2021001005',
                'password' => Hash::make('siswa123'),
                'role' => 'siswa',
            ],
            
            // Civitas Akademik Users
            [
                'id_user' => Str::uuid(),
                'username' => 'guru.matematika',
                'password' => Hash::make('guru123'),
                'role' => 'civitas_akademik',
            ],
            [
                'id_user' => Str::uuid(),
                'username' => 'guru.bahasa',
                'password' => Hash::make('guru123'),
                'role' => 'civitas_akademik',
            ],
            [
                'id_user' => Str::uuid(),
                'username' => 'kepala.sekolah',
                'password' => Hash::make('kepsek123'),
                'role' => 'civitas_akademik',
            ],
            
            // Penjual Users
            [
                'id_user' => Str::uuid(),
                'username' => 'kantin.budi',
                'password' => Hash::make('penjual123'),
                'role' => 'penjual',
            ],
            [
                'id_user' => Str::uuid(),
                'username' => 'kantin.sari',
                'password' => Hash::make('penjual123'),
                'role' => 'penjual',
            ],
            [
                'id_user' => Str::uuid(),
                'username' => 'kantin.ahmad',
                'password' => Hash::make('penjual123'),
                'role' => 'penjual',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}