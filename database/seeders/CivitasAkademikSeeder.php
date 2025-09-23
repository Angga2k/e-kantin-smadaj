<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CivitasAkademik;
use App\Models\User;
use Illuminate\Support\Str;

class CivitasAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get civitas akademik users
        $civitasUsers = User::where('role', 'civitas_akademik')->get();
        
        $civitasData = [
            [
                'npwp' => '12345678901234567890',
                'nama' => 'Dr. Siti Aminah, M.Pd',
                'tgl_lahir' => '1975-05-20',
                'jenis_kelamin' => 'P',
                'username' => 'guru.matematika',
            ],
            [
                'npwp' => '12345678901234567891',
                'nama' => 'Drs. Ahmad Subhan, M.A',
                'tgl_lahir' => '1978-08-15',
                'jenis_kelamin' => 'L',
                'username' => 'guru.bahasa',
            ],
            [
                'npwp' => '12345678901234567892',
                'nama' => 'Prof. Dr. Bambang Sutrisno, M.Pd',
                'tgl_lahir' => '1965-03-10',
                'jenis_kelamin' => 'L',
                'username' => 'kepala.sekolah',
            ],
            [
                'npwp' => '12345678901234567893',
                'nama' => 'Dra. Ratna Sari, M.Si',
                'tgl_lahir' => '1980-12-25',
                'jenis_kelamin' => 'P',
                'username' => 'guru.fisika',
            ],
            [
                'npwp' => '12345678901234567894',
                'nama' => 'S.Pd. Eko Prasetyo',
                'tgl_lahir' => '1985-07-08',
                'jenis_kelamin' => 'L',
                'username' => 'guru.olahraga',
            ],
            [
                'npwp' => '12345678901234567895',
                'nama' => 'Dra. Indira Kusuma, M.Pd',
                'tgl_lahir' => '1976-11-18',
                'jenis_kelamin' => 'P',
                'username' => 'guru.sejarah',
            ],
            [
                'npwp' => '12345678901234567896',
                'nama' => 'S.Kom. Rifki Hidayat',
                'tgl_lahir' => '1988-04-22',
                'jenis_kelamin' => 'L',
                'username' => 'guru.komputer',
            ],
            [
                'npwp' => '12345678901234567897',
                'nama' => 'Dra. Wulan Dari, M.Pd',
                'tgl_lahir' => '1982-09-14',
                'jenis_kelamin' => 'P',
                'username' => 'guru.biologi',
            ],
            [
                'npwp' => '12345678901234567898',
                'nama' => 'S.Pd. Agus Salim',
                'tgl_lahir' => '1979-01-30',
                'jenis_kelamin' => 'L',
                'username' => 'guru.ekonomi',
            ],
            [
                'npwp' => '12345678901234567899',
                'nama' => 'Dra. Lestari Ningrum, M.Pd',
                'tgl_lahir' => '1983-06-05',
                'jenis_kelamin' => 'P',
                'username' => 'guru.seni',
            ],
        ];

        foreach ($civitasData as $index => $data) {
            // Use existing user or create new one if not enough users
            if (isset($civitasUsers[$index])) {
                $userId = $civitasUsers[$index]->id_user;
            } else {
                // Create new user if needed
                $newUser = User::create([
                    'id_user' => Str::uuid(),
                    'username' => $data['username'],
                    'password' => bcrypt('guru123'),
                    'role' => 'civitas_akademik',
                ]);
                $userId = $newUser->id_user;
            }

            CivitasAkademik::create([
                'id' => Str::uuid(),
                'id_user' => $userId,
                'npwp' => $data['npwp'],
                'nama' => $data['nama'],
                'tgl_lahir' => $data['tgl_lahir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
            ]);
        }
    }
}
