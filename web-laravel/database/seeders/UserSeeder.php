<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nama' => 'Abi albani',
                'email' => 'abialbani69@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'karyawan',
                'created_at' => now()
            ]
        ]);
    }
}
