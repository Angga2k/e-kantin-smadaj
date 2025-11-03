<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Support\Str;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get penjual users
        $penjualUsers = User::where('role', 'penjual')->get();

        if ($penjualUsers->isEmpty()) {
            $this->command->warn('No penjual users found. Please run PenjualSeeder first.');
            return;
        }

        $barangData = [
            [
                'nama_barang' => 'Nasi Gudeg Jogja',
                'deskripsi_barang' => 'Nasi gudeg khas Yogyakarta dengan ayam, tahu, dan tempe',
                'jenis_barang' => 'Makanan Berat',
                'harga' => 15000,
                'stok' => 25,
                'foto_barang' => null,
                'kalori_kkal' => 450,
                'protein_g' => 25.5,
                'lemak_g' => 12.3,
                'karbohidrat_g' => 55.2,
                'serat_g' => 3.1,
                'gula_g' => 8.5,
            ],
            [
                'nama_barang' => 'Nasi Rawon',
                'deskripsi_barang' => 'Nasi rawon daging sapi dengan kerupuk dan tauge',
                'jenis_barang' => 'Makanan Berat',
                'harga' => 18000,
                'stok' => 20,
                'foto_barang' => null,
                'kalori_kkal' => 520,
                'protein_g' => 28.0,
                'lemak_g' => 15.2,
                'karbohidrat_g' => 58.4,
                'serat_g' => 4.2,
                'gula_g' => 6.8,
            ],
            [
                'nama_barang' => 'Mie Ayam Bakso',
                'deskripsi_barang' => 'Mie ayam dengan bakso, pangsit, dan sayuran',
                'jenis_barang' => 'Makanan Berat',
                'harga' => 12000,
                'stok' => 30,
                'foto_barang' => null,
                'kalori_kkal' => 380,
                'protein_g' => 22.3,
                'lemak_g' => 8.7,
                'karbohidrat_g' => 48.9,
                'serat_g' => 2.8,
                'gula_g' => 5.4,
            ],
            [
                'nama_barang' => 'Gado-Gado',
                'deskripsi_barang' => 'Sayuran segar dengan bumbu kacang dan kerupuk',
                'jenis_barang' => 'Makanan Ringan',
                'harga' => 10000,
                'stok' => 15,
                'foto_barang' => null,
                'kalori_kkal' => 280,
                'protein_g' => 12.5,
                'lemak_g' => 18.6,
                'karbohidrat_g' => 22.1,
                'serat_g' => 8.3,
                'gula_g' => 12.7,
            ],
            [
                'nama_barang' => 'Es Teh Manis',
                'deskripsi_barang' => 'Es teh manis segar untuk menemani makan',
                'jenis_barang' => 'Minuman',
                'harga' => 3000,
                'stok' => 50,
                'foto_barang' => null,
                'kalori_kkal' => 80,
                'protein_g' => 0.1,
                'lemak_g' => 0.0,
                'karbohidrat_g' => 20.5,
                'serat_g' => 0.0,
                'gula_g' => 20.0,
            ],
            [
                'nama_barang' => 'Jus Jeruk Fresh',
                'deskripsi_barang' => 'Jus jeruk segar tanpa pengawet dan pewarna',
                'jenis_barang' => 'Minuman',
                'harga' => 8000,
                'stok' => 20,
                'foto_barang' => null,
                'kalori_kkal' => 110,
                'protein_g' => 1.7,
                'lemak_g' => 0.5,
                'karbohidrat_g' => 25.8,
                'serat_g' => 0.5,
                'gula_g' => 21.0,
            ],
            [
                'nama_barang' => 'Pisang Goreng',
                'deskripsi_barang' => 'Pisang goreng crispy dengan topping keju dan coklat',
                'jenis_barang' => 'Snack',
                'harga' => 5000,
                'stok' => 35,
                'foto_barang' => null,
                'kalori_kkal' => 180,
                'protein_g' => 3.2,
                'lemak_g' => 8.4,
                'karbohidrat_g' => 26.7,
                'serat_g' => 2.1,
                'gula_g' => 15.3,
            ],
            [
                'nama_barang' => 'Martabak Manis Mini',
                'deskripsi_barang' => 'Martabak manis mini dengan berbagai topping',
                'jenis_barang' => 'Snack',
                'harga' => 7000,
                'stok' => 25,
                'foto_barang' => null,
                'kalori_kkal' => 220,
                'protein_g' => 5.8,
                'lemak_g' => 12.1,
                'karbohidrat_g' => 24.5,
                'serat_g' => 1.2,
                'gula_g' => 18.9,
            ],
            [
                'nama_barang' => 'Soto Ayam',
                'deskripsi_barang' => 'Soto ayam kuning dengan nasi, telur, dan kerupuk',
                'jenis_barang' => 'Makanan Berat',
                'harga' => 13000,
                'stok' => 18,
                'foto_barang' => null,
                'kalori_kkal' => 420,
                'protein_g' => 26.8,
                'lemak_g' => 11.2,
                'karbohidrat_g' => 52.3,
                'serat_g' => 2.9,
                'gula_g' => 4.7,
            ],
            [
                'nama_barang' => 'Bakpao Isi Ayam',
                'deskripsi_barang' => 'Bakpao lembut dengan isi ayam bumbu gurih',
                'jenis_barang' => 'Snack',
                'harga' => 6000,
                'stok' => 40,
                'foto_barang' => null,
                'kalori_kkal' => 160,
                'protein_g' => 8.5,
                'lemak_g' => 4.2,
                'karbohidrat_g' => 22.8,
                'serat_g' => 1.8,
                'gula_g' => 3.2,
            ],
        ];

        foreach ($barangData as $index => $data) {
            // Distribute products among penjual users
            $penjualUser = $penjualUsers[$index % $penjualUsers->count()];

            Barang::create([
                'id_barang' => Str::uuid(),
                'id_user_penjual' => $penjualUser->id_user,
                'nama_barang' => $data['nama_barang'],
                'deskripsi_barang' => $data['deskripsi_barang'],
                'jenis_barang' => $data['jenis_barang'],
                'harga' => $data['harga'],
                'stok' => $data['stok'],
                'foto_barang' => $data['foto_barang'],
                'kalori_kkal' => $data['kalori_kkal'],
                'protein_g' => $data['protein_g'],
                'lemak_g' => $data['lemak_g'],
                'karbohidrat_g' => $data['karbohidrat_g'],
                'serat_g' => $data['serat_g'],
                'gula_g' => $data['gula_g'],
            ]);
        }
    }
}
