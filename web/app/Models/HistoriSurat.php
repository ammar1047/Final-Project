<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriSurat extends Model
{
    protected $table = 'histori_surat';

    protected $fillable = [
        'user_id',
        'template_id',
        'kategori_id',
        'nomor_surat',
        'keterangan',
        'file_hasil_path',
        'tanggal_diterbitkan',
        'status',
    ];    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriTemplate::class, 'kategori_id');
    }
    public function template()
    {
        return $this->belongsTo(TemplateSurat::class, 'template_id');
    }
}