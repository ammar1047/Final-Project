<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TemplateSurat;

class TemplateSuratApiController extends Controller
{
    public function index(Request $request)
    {
        $query = TemplateSurat::with('kategori');

        // Jika ingin filter berdasarkan kategori_id
        if ($request->has('kategori_id') && $request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Jika ingin filter berdasarkan nama_kategori (opsional)
        if ($request->has('kategori') && $request->kategori) {
            $query->whereHas('kategori', function($q) use ($request) {
                $q->where('nama_kategori', $request->kategori);
            });
        }

        return response()->json($query->get());
    }
}
