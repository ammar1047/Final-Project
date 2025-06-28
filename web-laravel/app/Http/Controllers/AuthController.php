<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('login');
    }

    public function prosesLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if ($user->role !== 'admin') {
                \Log::warning("LOGIN DITOLAK UNTUK NON-ADMIN: " . $user->email);
                return redirect()->route('login.form')->with('error', 'Hanya admin yang dapat login.');
            }

            Session::put('user', [
                'id' => $user->id,
                'nama' => $user->nama,
                'email' => $user->email,
                'role' => $user->role,
            ]);
            \Log::info("LOGIN BERHASIL DENGAN: " . $request->password);
            return redirect()->route('dashboard');
        }

        \Log::warning("LOGIN GAGAL DENGAN: " . $request->password);
        return redirect()->route('login.form')->with('error', 'Email atau password salah.');
    }



    public function logout()
    {
        Session::forget('user');
        return redirect()->route('login.form');
        
    }
    
    public function apiLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
    
}
