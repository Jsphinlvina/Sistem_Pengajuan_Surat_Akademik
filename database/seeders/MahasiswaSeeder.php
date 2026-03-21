<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mahasiswas')->insert([
            [
                'nama' => 'Andi Saputra',
                'email' => 'andi.saputra@maranatha.ac.id',
                'nrp' => '2272001',
                'alamat' => 'Bandung',
                'program_studi_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Siti Aminah',
                'email' => 'siti.aminah@maranatha.ac.id',
                'nrp' => '2272002',
                'alamat' => 'Jakarta',
                'program_studi_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@maranatha.ac.id',
                'nrp' => '2273001',
                'alamat' => 'Surabaya',
                'program_studi_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Rina Putri',
                'email' => 'rina.putri@maranatha.ac.id',
                'nrp' => '2273002',
                'alamat' => 'Yogyakarta',
                'program_studi_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
