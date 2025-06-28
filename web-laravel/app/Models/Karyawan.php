<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    
    protected $table = 'karyawan';

    use HasFactory;
    protected $fillable = [
        'user_id',
        'nik',
        'nama_lengkap',
        'email',
    ];

    public function pengajuanSurat()
    {
        return $this->hasMany(PengajuanSurat::class, 'user_id', 'user_id');
    }
}
