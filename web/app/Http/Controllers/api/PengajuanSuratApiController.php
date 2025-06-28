<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanSurat;

class PengajuanSuratApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only('getStatusPengajuan');
    }
    public function store(Request $request)
    {
        // Validasi data dari frontend
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'template_id' => 'required|exists:template_surat,id',
            'tanggal_pengajuan' => 'required|date',
            'status' => 'required|in:menunggu,disetujui,ditolak',
            'keterangan' => 'nullable|string',
            'tanggal_berlaku' => 'required|date',
            'tanggal_berakhir' => 'required|date'
        ]);

        // Simpan ke database
        PengajuanSurat::create([
            'user_id' => $request->user_id,
            'template_id' => $request->template_id,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'tanggal_berlaku' => $request->tanggal_berlaku,
            'tanggal_berakhir' => $request->tanggal_berakhir,
        ]);

        return response()->json(['message' => 'Pengajuan surat berhasil disimpan'], 201);
    }
    public function getStatusPengajuan()
    {
        $user = Auth::guard('api')->user();

        $pengajuan = \App\Models\PengajuanSurat::with('template')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($pengajuan);
    }
    public function getRiwayat()
    {
        $user = \Auth::guard('api')->user();

        $riwayat = \App\Models\PengajuanSurat::with('template')
            ->where('user_id', $user->id)
            ->where('status', '!=', 'menunggu') // misalnya hanya status yang sudah diproses
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($riwayat);
    }

}

