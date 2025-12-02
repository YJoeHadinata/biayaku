<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CostAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $operational = [
            ['nama' => 'Gaji', 'tipe' => 'operational', 'deskripsi' => 'Biaya gaji karyawan'],
            ['nama' => 'Sewa', 'tipe' => 'operational', 'deskripsi' => 'Biaya sewa tempat usaha'],
            ['nama' => 'Listrik', 'tipe' => 'operational', 'deskripsi' => 'Biaya listrik'],
            ['nama' => 'Transportasi', 'tipe' => 'operational', 'deskripsi' => 'Biaya transportasi'],
            ['nama' => 'ATK', 'tipe' => 'operational', 'deskripsi' => 'Alat tulis kantor'],
            ['nama' => 'Telekomunikasi', 'tipe' => 'operational', 'deskripsi' => 'Biaya telepon/internet'],
            ['nama' => 'Pemeliharaan', 'tipe' => 'operational', 'deskripsi' => 'Biaya pemeliharaan peralatan'],
        ];

        $miscellaneous = [
            ['nama' => 'Administrasi', 'tipe' => 'miscellaneous', 'deskripsi' => 'Biaya administrasi'],
            ['nama' => 'Denda', 'tipe' => 'miscellaneous', 'deskripsi' => 'Biaya denda'],
            ['nama' => 'Perizinan', 'tipe' => 'miscellaneous', 'deskripsi' => 'Biaya perizinan usaha'],
            ['nama' => 'Asuransi', 'tipe' => 'miscellaneous', 'deskripsi' => 'Biaya asuransi'],
            ['nama' => 'Lain-lain', 'tipe' => 'miscellaneous', 'deskripsi' => 'Biaya lainnya'],
        ];

        \App\Models\CostAccount::insert(array_merge($operational, $miscellaneous));
    }
}
