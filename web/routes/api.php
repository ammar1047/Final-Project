
<?php
use App\Http\Controllers\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\TemplateSuratApiController;
use App\Http\Controllers\api\PengajuanSuratApiController;
use App\Http\Controllers\api\RiwayatSuratApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth:api')->get('/pengajuan/status', [PengajuanSuratApiController::class, 'getStatusPengajuan']);
Route::middleware('auth:api')->get('/pengajuan/riwayat', [\App\Http\Controllers\api\RiwayatSuratApiController::class, 'getRiwayatUser']);
Route::middleware('auth:api')->get('/me', function (Request $request) {
    return $request->user();
});
Route::get('/template-surat', [TemplateSuratApiController::class, 'index']);
Route::post('/pengajuan-surat', [PengajuanSuratApiController::class, 'store']);


Route::post('/login', [AuthController::class, 'apiLogin']);
Route::middleware('auth:api')->post('/logout', function () {
    auth()->logout();
    return response()->json(['message' => 'Successfully logged out']);
});