<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\HistoriSurat;
use Illuminate\Support\Facades\DB;

class PengajuanSuratController extends Controller
{
    public function index(Request $request)
    {
        $query = PengajuanSurat::with(['karyawan', 'template.kategori']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('karyawan', function ($q) use ($search) {
                $q->where('nik', 'like', '%' . $search . '%')
                ->orWhere('nama_lengkap', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('kategori') && $request->kategori != '') {
            $kategori = $request->kategori;
            $query->whereHas('template.kategori', function ($q) use ($kategori) {
                $q->where('nama_kategori', $kategori);
            });
        }

        $pengajuan = $query->orderByDesc('created_at')->paginate(10);
        $kategoriList = \App\Models\KategoriTemplate::all();

        return view('pengajuan-surat', compact('pengajuan', 'kategoriList'));
    }

    public function previewNomorSurat($kategoriId)
    {
        $kodeMap = [1 => 'SKK', 2 => 'SPK', 3 => 'SCK', 4 => 'SR'];
        $kode = $kodeMap[$kategoriId] ?? 'XXX';
        $tahun = now()->year;

        $log = DB::table('log_nomor_surat')
            ->where('kategori_id', $kategoriId)
            ->where('tahun', $tahun)
            ->first();

        $next = $log ? $log->last_number + 1 : 1;
        $preview = 'HRD/' . $kode . '/' . $tahun . '/' . str_pad($next, 3, '0', STR_PAD_LEFT);

        return response()->json(['preview' => $preview]);
    }

    private function generateNomorSurat($kategoriId)
    {
        $kodeMap = [1 => 'SKK', 2 => 'SPK', 3 => 'SCK', 4 => 'SR'];
        $kode = $kodeMap[$kategoriId] ?? 'XXX';
        $tahun = now()->year;

        $log = DB::table('log_nomor_surat')
            ->where('kategori_id', $kategoriId)
            ->where('tahun', $tahun)
            ->first();

        $last = $log ? $log->last_number + 1 : 1;

        DB::table('log_nomor_surat')->updateOrInsert(
            ['kategori_id' => $kategoriId, 'tahun' => $tahun],
            ['last_number' => $last, 'updated_at' => now()]
        );

        return 'HRD/' . $kode . '/' . $tahun . '/' . str_pad($last, 3, '0', STR_PAD_LEFT);
    }

    public function setujui(Request $request, $id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);
        $kategoriId = $request->input('kategori_id');
        $nomorSurat = $this->generateNomorSurat($kategoriId);

        HistoriSurat::create([
            'user_id' => $pengajuan->user_id,
            'template_id' => $pengajuan->template_id,
            'kategori_id' => $kategoriId,
            'nomor_surat' => $nomorSurat,
            'keterangan' => $pengajuan->keterangan,
            'tanggal_diterbitkan' => now(),
            'status' => 'selesai'
        ]);

        $pengajuan->delete();

        return redirect()->back()->with('success', 'Surat disetujui dan nomor berhasil digunakan.');
    }

    public function tolak(Request $request, $id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        HistoriSurat::create([
            'user_id' => $pengajuan->user_id,
            'template_id' => $pengajuan->template_id,
            'kategori_id' => $pengajuan->template->kategori_id,
            'nomor_surat' => 'Ditolak', // bisa kosong / placeholder
            'keterangan' => $request->keterangan,
            'tanggal_diterbitkan' => now(),
            'status' => 'ditolak',
        ]);

        $pengajuan->delete();

        return redirect()->back()->with('info', 'Pengajuan ditolak.');
    }

}
