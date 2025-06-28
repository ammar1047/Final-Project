<?php

namespace App\Models;
use App\Models\Karyawan;
use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    protected $table = 'pengajuan_surat';

    protected $fillable = [
        'user_id',
        'template_id',
        'tanggal_pengajuan',
        'status',
        'keterangan',
        'tanggal_berlaku',
        'tanggal_berakhir'
    ];


    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'user_id', 'user_id');
    }

    public function template()
    {
        return $this->belongsTo(TemplateSurat::class, 'template_id');
    }
}
