<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HistoriSurat;
use Illuminate\Support\Facades\Auth;

class RiwayatSuratApiController extends Controller
{
    public function getRiwayatUser()
    {
        $user = Auth::guard('api')->user();

        $riwayat = HistoriSurat::where('user_id', $user->id)
                    ->with('template') // relasi ke tabel template jika ada
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json($riwayat);
    }
}
