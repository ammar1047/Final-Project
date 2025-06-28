<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->get();

        foreach ($users as $user) {
            DB::table('karyawan')->insert([
                'nik' => 'NIK-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'user_id' => $user->id,
                'nama_lengkap' => $user->nama ?? $user->name ?? 'Nama Default',
                'email' => $user->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
