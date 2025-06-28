<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateSurat extends Model
{
    protected $table = 'template_surat';

    protected $fillable = [
        'judul_template',
        'file_path',
        'format_file',
        'kategori_id',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriTemplate::class, 'kategori_id');
    }
}

