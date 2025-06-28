<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EditSuratController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\HistoriSuratController;
use App\Http\Controllers\PengajuanSuratController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
});
Route::get('/download-template/{filename}', function ($filename) {
    $allowed = ['pdf', 'docx'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        abort(403, 'Tipe file tidak diizinkan.');
    }

    $path = public_path('templates/' . $filename);
    if (file_exists($path)) {
        return response()->download($path);
    }

    abort(404, 'File tidak ditemukan.');
});



Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');



Route::get('/login', [AuthController::class, 'loginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'prosesLogin'])->name('login.proses');

// Hanya pakai route POST untuk logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');
// routes/web.php


Route::get('/edit-surat',  [EditSuratController::class, 'index'])->name('surat.index');
Route::delete('/edit-surat/{id}', [EditSuratController::class, 'destroy'])->name('surat.destroy');



Route::get('/test-session', function () {
    session()->flash('status', 'Ini pesan tes dari session.');
    return redirect('/login');
});
Route::post('/edit-surat', [EditSuratController::class, 'store'])->name('surat.store');
Route::put('/edit-surat/{id}', [EditSuratController::class, 'update'])->name('surat.update');
Route::get('/histori-surat', [HistoriSuratController::class, 'index'])->name('histori.surat');


Route::get('/pengajuan-surat', [PengajuanSuratController::class, 'index'])->name('pengajuan.surat');
Route::post('/pengajuan-surat/{id}/setujui', [PengajuanSuratController::class, 'setujui'])->name('pengajuan.setujui');
Route::post('/pengajuan-surat/{id}/tolak', [PengajuanSuratController::class, 'tolak'])->name('pengajuan.tolak');

Route::get('/generate-nomor-surat/{kategori_id}', [PengajuanSuratController::class, 'generateNomorAjax'])->name('generate.nomor.surat');
Route::get('/preview-nomor-surat/{kategori_id}', [PengajuanSuratController::class, 'previewNomorSurat'])->name('preview.nomor.surat');

Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
Route::post('/karyawan-store', [KaryawanController::class, 'store'])->name('karyawan.store');
Route::post('/admin-store', [KaryawanController::class, 'storeAdmin'])->name('admin.store');
Route::delete('/karyawan/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
Route::put('/karyawan/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
