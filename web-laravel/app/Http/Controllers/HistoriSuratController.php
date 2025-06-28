<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoriSurat;
use App\Models\KategoriTemplate;
use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;

class HistoriSuratController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('histori_surat')
            ->join('karyawan', 'histori_surat.user_id', '=', 'karyawan.user_id')
            ->join('kategori_template', 'histori_surat.kategori_id', '=', 'kategori_template.id')
            ->select(
                'histori_surat.nomor_surat',
                'karyawan.nik',
                'karyawan.nama_lengkap',
                'kategori_template.nama_kategori as kategori',
                'histori_surat.keterangan',
                'histori_surat.tanggal_diterbitkan',
                'histori_surat.status'
            );
            

        // Filter Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('karyawan.nik', 'like', '%' . $request->search . '%')
                  ->orWhere('karyawan.nama_lengkap', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_template.nama_kategori', $request->kategori);
        }

        $histori = $query->orderByDesc('histori_surat.tanggal_diterbitkan')->paginate(10);

        $kategoriList = DB::table('kategori_template')->pluck('nama_kategori');

        return view('histori-surat', compact('histori', 'kategoriList'));
    }
}
