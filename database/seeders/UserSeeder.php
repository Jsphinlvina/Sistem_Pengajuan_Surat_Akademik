<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'kode' => 'ADM001',
                'name' => 'Admin Utama',
                'email' => 'admin@example.com',
                'role' => 0,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'program_studi_id' => null,
                'status' => true,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'STF001',
                'name' => 'Staff Akademik',
                'email' => 'staff1@example.com',
                'role' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'program_studi_id' => 1,
                'remember_token' => null,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'STF002',
                'name' => 'Staff Akademik',
                'email' => 'staff2@example.com',
                'role' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'program_studi_id' => 2,
                'remember_token' => null,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
