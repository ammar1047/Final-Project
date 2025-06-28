<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PengajuanSurat;
use App\Models\HistoriSurat;
use App\Models\KategoriTemplate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $range = 'monthly'; // Dipaksa jadi bulanan

        $totalKaryawan = User::where('role', 'karyawan')->count();
        $totalDraftSurat = PengajuanSurat::where('status', 'menunggu')->count();
        $totalNoSuratDigunakan = HistoriSurat::whereNotNull('nomor_surat')->count();

        // =============================
        // BAR CHART: Overall Statistic (per minggu)
        // =============================
        $barWeeks = collect(range(0, 3))->map(function ($n) {
            return Carbon::now()->startOfWeek()->subWeeks(3 - $n)->format('W');
        });

        $barData = [
            'minggu' => [],
            'karyawan' => [],
            'pengajuan' => [],
            'nosurat' => []
        ];

        foreach ($barWeeks as $week) {
            $barData['minggu'][] = "Minggu " . $week;

            $barData['karyawan'][] = User::where('role', 'karyawan')
                ->whereRaw("WEEK(created_at, 1) = ?", [$week])->count();

            $barData['pengajuan'][] = PengajuanSurat::whereRaw("WEEK(tanggal_pengajuan, 1) = ?", [$week])->count();

            $barData['nosurat'][] = HistoriSurat::where('status', 'selesai')
                ->whereRaw("WEEK(tanggal_diterbitkan, 1) = ?", [$week])->count();
        }

        // =============================
        // LINE CHART: Histori Surat yang di-ACC (selesai) per BULAN
        // =============================
        $kategoriList = KategoriTemplate::pluck('nama_kategori')->toArray();
        $lineData = [];

        $months = collect(range(0, 5))->map(fn($i) => Carbon::now()->subMonths(5 - $i));
        $monthLabels = $months->map(fn($m) => $m->format('M'))->toArray();
        $months = $months->map(fn($m) => $m->format('Y-m'));

        foreach ($kategoriList as $kategori) {
            $lineData[$kategori] = [];
            foreach ($months as $month) {
                $lineData[$kategori][] = HistoriSurat::join('kategori_template', 'histori_surat.kategori_id', '=', 'kategori_template.id')
                    ->where('kategori_template.nama_kategori', 'like', '%' . $kategori . '%')
                    ->where('status', 'selesai')
                    ->whereRaw("DATE_FORMAT(tanggal_diterbitkan, '%Y-%m') = ?", [$month])
                    ->count();
            }
        }

        return view('dashboard', compact(
            'totalKaryawan',
            'totalDraftSurat',
            'totalNoSuratDigunakan',
            'barData',
            'lineData',
            'monthLabels'
        ));
    }
}
