<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SiswaSeeder::class,
            CivitasAkademikSeeder::class,
            PenjualSeeder::class,
            BarangSeeder::class,
        ]);

        $this->command->info('All seeders completed successfully!');
        $this->command->info('Summary:');
        $this->command->info('- Users: ' . User::count());
        $this->command->info('- Siswa: ' . \App\Models\Siswa::count());
        $this->command->info('- Civitas Akademik: ' . \App\Models\CivitasAkademik::count());
        $this->command->info('- Penjual: ' . \App\Models\Penjual::count());
        $this->command->info('- Barang: ' . \App\Models\Barang::count());
    }
}
