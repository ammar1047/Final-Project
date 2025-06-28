<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriTemplate extends Model
{
    protected $table = 'kategori_template';

    protected $fillable = ['nama_kategori'];

    public function templates()
    {
        return $this->hasMany(TemplateSurat::class, 'kategori_id');
    }
}

