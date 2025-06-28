<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HistoriSuratSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('histori_surat')->insert([
            [
                'user_id' => 5,
                'template_id' => 26,
                'kategori_id' => 3,
                'nomor_surat' => 'SCK/2025/01',
                'keterangan' => 'Mengajukan cuti tahunan selama 3 hari.',
                'file_hasil_path' => null,
                'tanggal_diterbitkan' => Carbon::now()->subDays(3),
                'status' => 'selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'template_id' => 26,
                'kategori_id' => 3,
                'nomor_surat' => 'SCK/2025/02',
                'keterangan' => 'Cuti karena keperluan keluarga.',
                'file_hasil_path' => null,
                'tanggal_diterbitkan' => Carbon::now()->subDays(2),
                'status' => 'ditolak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'template_id' => 26,
                'kategori_id' => 3,
                'nomor_surat' => 'SCK/2025/03',
                'keterangan' => 'Cuti karena sakit.',
                'file_hasil_path' => null,
                'tanggal_diterbitkan' => Carbon::now()->subDay(),
                'status' => 'dibatalkan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
    }
}
