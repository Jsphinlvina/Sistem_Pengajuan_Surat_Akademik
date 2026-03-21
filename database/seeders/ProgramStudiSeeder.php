<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('program_studis')->insert([
            [
                'kode' => '72',
                'nama' => 'Teknik Informatika',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => '73',
                'nama' => 'Sistem Informasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
