<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Str;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get siswa users
        $siswaUsers = User::where('role', 'siswa')->get();
        
        $siswaData = [
            [
                'nisn' => '2021001001',
                'nama' => 'Ahmad Fauzi Rahman',
                'tgl_lahir' => '2005-03-15',
                'jenis_kelamin' => 'L',
            ],
            [
                'nisn' => '2021001002',
                'nama' => 'Siti Nurhaliza',
                'tgl_lahir' => '2005-07-22',
                'jenis_kelamin' => 'P',
            ],
            [
                'nisn' => '2021001003',
                'nama' => 'Budi Santoso',
                'tgl_lahir' => '2005-01-10',
                'jenis_kelamin' => 'L',
            ],
            [
                'nisn' => '2021001004',
                'nama' => 'Dewi Sartika',
                'tgl_lahir' => '2005-11-05',
                'jenis_kelamin' => 'P',
            ],
            [
                'nisn' => '2021001005',
                'nama' => 'Rizki Pratama',
                'tgl_lahir' => '2005-09-18',
                'jenis_kelamin' => 'L',
            ],
            [
                'nisn' => '2022001001',
                'nama' => 'Maya Sari Indah',
                'tgl_lahir' => '2006-02-14',
                'jenis_kelamin' => 'P',
            ],
            [
                'nisn' => '2022001002',
                'nama' => 'Andi Setiawan',
                'tgl_lahir' => '2006-04-30',
                'jenis_kelamin' => 'L',
            ],
            [
                'nisn' => '2022001003',
                'nama' => 'Lina Marlina',
                'tgl_lahir' => '2006-08-12',
                'jenis_kelamin' => 'P',
            ],
            [
                'nisn' => '2022001004',
                'nama' => 'Doni Prasetyo',
                'tgl_lahir' => '2006-06-25',
                'jenis_kelamin' => 'L',
            ],
            [
                'nisn' => '2022001005',
                'nama' => 'Fitri Handayani',
                'tgl_lahir' => '2006-12-03',
                'jenis_kelamin' => 'P',
            ],
        ];

        foreach ($siswaData as $index => $data) {
            // Use existing user or create new one if not enough users
            if (isset($siswaUsers[$index])) {
                $userId = $siswaUsers[$index]->id_user;
            } else {
                // Create new user if needed
                $newUser = User::create([
                    'id_user' => Str::uuid(),
                    'username' => $data['nisn'],
                    'password' => bcrypt('siswa123'),
                    'role' => 'siswa',
                ]);
                $userId = $newUser->id_user;
            }

            Siswa::create([
                'id' => Str::uuid(),
                'id_user' => $userId,
                'nisn' => $data['nisn'],
                'nama' => $data['nama'],
                'tgl_lahir' => $data['tgl_lahir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
            ]);
        }
    }
}
