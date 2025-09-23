<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penjual;
use App\Models\User;
use Illuminate\Support\Str;

class PenjualSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get penjual users
        $penjualUsers = User::where('role', 'penjual')->get();
        
        $penjualData = [
            [
                'nama_toko' => 'Kantin Bu Budi',
                'nama_penanggungjawab' => 'Budi Rahayu',
                'no_rekening' => '1234567890',
                'nama_bank' => 'BCA',
                'username' => 'kantin.budi',
            ],
            [
                'nama_toko' => 'Warung Sari Rasa',
                'nama_penanggungjawab' => 'Sari Dewi',
                'no_rekening' => '2345678901',
                'nama_bank' => 'Mandiri',
                'username' => 'kantin.sari',
            ],
            [
                'nama_toko' => 'Ahmad Jaya Snack',
                'nama_penanggungjawab' => 'Ahmad Wijaya',
                'no_rekening' => '3456789012',
                'nama_bank' => 'BRI',
                'username' => 'kantin.ahmad',
            ],
            [
                'nama_toko' => 'Kedai Mama Ina',
                'nama_penanggungjawab' => 'Ina Suhartini',
                'no_rekening' => '4567890123',
                'nama_bank' => 'BNI',
                'username' => 'kantin.ina',
            ],
            [
                'nama_toko' => 'Warung Pak Joko',
                'nama_penanggungjawab' => 'Joko Susilo',
                'no_rekening' => '5678901234',
                'nama_bank' => 'CIMB Niaga',
                'username' => 'kantin.joko',
            ],
            [
                'nama_toko' => 'Kantin Sehat Alami',
                'nama_penanggungjawab' => 'Maya Sari',
                'no_rekening' => '6789012345',
                'nama_bank' => 'Danamon',
                'username' => 'kantin.maya',
            ],
            [
                'nama_toko' => 'Toko Roti Bahagia',
                'nama_penanggungjawab' => 'Andi Kusuma',
                'no_rekening' => '7890123456',
                'nama_bank' => 'Permata',
                'username' => 'kantin.andi',
            ],
            [
                'nama_toko' => 'Warung Minuman Segar',
                'nama_penanggungjawab' => 'Lina Mariana',
                'no_rekening' => '8901234567',
                'nama_bank' => 'OCBC NISP',
                'username' => 'kantin.lina',
            ],
            [
                'nama_toko' => 'Kantin Tradisional Nusantara',
                'nama_penanggungjawab' => 'Doni Setiawan',
                'no_rekening' => '9012345678',
                'nama_bank' => 'Bank Jateng',
                'username' => 'kantin.doni',
            ],
            [
                'nama_toko' => 'Kedai Kopi & Snack',
                'nama_penanggungjawab' => 'Fitri Rahayu',
                'no_rekening' => '0123456789',
                'nama_bank' => 'BSI',
                'username' => 'kantin.fitri',
            ],
        ];

        foreach ($penjualData as $index => $data) {
            // Use existing user or create new one if not enough users
            if (isset($penjualUsers[$index])) {
                $userId = $penjualUsers[$index]->id_user;
            } else {
                // Create new user if needed
                $newUser = User::create([
                    'id_user' => Str::uuid(),
                    'username' => $data['username'],
                    'password' => bcrypt('penjual123'),
                    'role' => 'penjual',
                ]);
                $userId = $newUser->id_user;
            }

            Penjual::create([
                'id' => Str::uuid(),
                'id_user' => $userId,
                'nama_toko' => $data['nama_toko'],
                'nama_penanggungjawab' => $data['nama_penanggungjawab'],
                'no_rekening' => $data['no_rekening'],
                'nama_bank' => $data['nama_bank'],
            ]);
        }
    }
}
