<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemplateSurat;
use App\Models\KategoriTemplate;
class EditSuratController extends Controller
{
    public function index(Request $request)
    {
        $query = TemplateSurat::with('kategori');

        if ($request->has('kategori') && $request->kategori) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->has('search') && $request->search) {
            $query->where('judul_template', 'like', '%' . $request->search . '%');
        }

        $templates = $query->paginate(10)->appends($request->all()); // penting: paginate + appends

        $kategori = KategoriTemplate::all();

        return view('edit-surat', compact('templates', 'kategori'));
    }

    public function destroy($id)
    {
        $template = TemplateSurat::findOrFail($id);
        $template->delete();

        return redirect()->route('surat.index')->with('success', 'Template berhasil dihapus.');
    }
    public function store(Request $request)
    {
        $request->validate([
            'judul_template' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_template,id',
            'file_template' => 'required|file|mimes:pdf,docx|max:2048',
        ]);
    
        $file = $request->file('file_template');
        $fileName = time() . '_' . $file->getClientOriginalName(); // Nama file unik
    
        // Simpan ke folder public/templates (yang berada di dalam public_html)
        $destinationPath = public_path('templates');
    
        // Pastikan foldernya ada
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true); // Gunakan 0755 agar bisa diakses web
        }
    
        // Pindahkan file ke public/templates
        $file->move($destinationPath, $fileName);
    
        // Simpan path relatif agar bisa dipanggil via URL
        TemplateSurat::create([
            'judul_template' => $request->judul_template,
            'kategori_id' => $request->kategori_id,
            'file_path' => 'templates/' . $fileName, // ⬅️ cukup seperti ini
        ]);
    
        return redirect()->route('surat.index')->with('success', 'Surat berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_template' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_template,id',
        ]);

        $template = TemplateSurat::findOrFail($id);
        $template->update([
            'judul_template' => $request->judul_template,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect()->route('surat.index')->with('success', 'Surat berhasil diperbarui.');
    }



    
}


