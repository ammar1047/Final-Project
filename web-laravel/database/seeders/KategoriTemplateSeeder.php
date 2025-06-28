<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategori_template')->insert([
            ['nama_kategori' => 'Surat Keterangan Karyawan'],
            ['nama_kategori' => 'Pengajuan Keluhan'],
            ['nama_kategori' => 'Permohonan Cuti'],
            ['nama_kategori' => 'Surat Rekomendasi'],
        ]);
    }
}
