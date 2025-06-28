<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Karyawan;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Karyawan::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
    
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        $karyawan = \App\Models\Karyawan::with('user')->get();

        return view('karyawan', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'nama' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'karyawan',
        ]);

        $nik = $this->generateNIK();

        Karyawan::create([
            'nik' => $nik,
            'user_id' => $user->id,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'nama' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        return redirect()->back()->with('success', 'Admin berhasil ditambahkan.');
    }

    private function generateNIK()
    {
        $last = Karyawan::latest('id')->first();
        $nextId = $last ? $last->id + 1 : 1;
        return 'NIK-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }
    public function destroy($id)
    {
        $karyawan = \App\Models\Karyawan::findOrFail($id);

        // Hapus relasi user jika diperlukan
        $karyawan->user()->delete();

        // Hapus data karyawan
        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $karyawan = Karyawan::findOrFail($id);
        $karyawan->nama_lengkap = $request->nama_lengkap;
        $karyawan->save();

        $user = $karyawan->user;
        $user->email = $request->email;
        $user->nama = $request->nama_lengkap;

        if ($request->filled('password')) {
            $user->password = \Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Data karyawan berhasil diperbarui.');
    }


}
