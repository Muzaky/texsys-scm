<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Penjualan;
use App\Models\ProdukJadi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();
        $periodeAktif = $request->input('periode', 'semua');

 
        $startDate;
        $keteranganPeriode;
        $numberOfMonths; 

        if ($periodeAktif === '1M') {
            $startDate = $now->copy()->subMonth();
            $keteranganPeriode = "1 Bulan Terakhir";
            $numberOfMonths = 2; 
        } elseif ($periodeAktif === '3M') {
            $startDate = $now->copy()->subMonths(3);
            $keteranganPeriode = "3 Bulan Terakhir";
            $numberOfMonths = 4; 
        } elseif ($periodeAktif === '6M') {
            $startDate = $now->copy()->subMonths(6);
            $keteranganPeriode = "6 Bulan Terakhir";
            $numberOfMonths = 7;
        } elseif ($periodeAktif === '1T') {
            $startDate = $now->copy()->subYear();
            $keteranganPeriode = "1 Tahun Terakhir";
            $numberOfMonths = 13; 
        } else { 
            $startDate = $now->copy()->subYears(2);
            $keteranganPeriode = "2 Tahun Terakhir"; 
            $numberOfMonths = 24;
        }

      
        $queryPenjualan = Penjualan::query()->with('produkJadi')
                                ->where('tanggal_penjualan', '>=', $startDate->copy()->startOfDay())
                                ->where('tanggal_penjualan', '<=', $now->copy()->endOfDay());

     
        $penjualanPadaPeriode = $queryPenjualan->clone()->orderBy('created_at', 'desc')->get();
        $penjualanPadaPeriodePaginate = $queryPenjualan->clone()->orderBy('created_at', 'desc')->paginate(10);

        $totalHargaPenjualanPeriode = $penjualanPadaPeriode->sum('total_harga');
        $totalKuantitasPenjualanPeriode = $penjualanPadaPeriode->sum('jumlah_terjual');

      
        $salesDataForChart = Penjualan::select(
                DB::raw('YEAR(tanggal_penjualan) as year'),
                DB::raw('MONTH(tanggal_penjualan) as month'),
                DB::raw('SUM(total_harga) as total_penjualan')
            )
            ->where('tanggal_penjualan', '>=', $startDate->copy()->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $salesByMonth = [];
        foreach ($salesDataForChart as $data) {
            $salesByMonth[$data->year . '-' . $data->month] = $data->total_penjualan;
        }

        $chartLabels = [];
        $chartData = [];
        $labelFormat = ($numberOfMonths > 12) ? 'MMM YY' : 'MMM';

        for ($i = $numberOfMonths - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->year . '-' . $date->month;
            $chartLabels[] = $date->isoFormat($labelFormat);
            $chartData[] = $salesByMonth[$monthKey] ?? 0;
        }

        $monthlySalesData = [
            'labels' => $chartLabels,
            'data' => $chartData,
        ];
        
        
        $bahanbaku = BahanBaku::all();
        $totalNilaiBahanBaku = $bahanbaku->sum(fn($item) => $item->stok_level * $item->harga);
        $bahanBakuTeratas = $bahanbaku->sortByDesc('stok_level')->take(3);

        $produkJadi = ProdukJadi::all();
        $totalNilaiProdukJadi = $produkJadi->sum(fn($item) => $item->stok_level * $item->harga);

        return view('dashboard.dashboard', [
            'bahanBaku' => $bahanbaku,
            'produkJadi' => $produkJadi,
            'totalNilaiBahanBaku' => $totalNilaiBahanBaku,
            'totalNilaiProdukJadi' => $totalNilaiProdukJadi,
            'bahanBakuTeratas' => $bahanBakuTeratas,
            'totalHargaPenjualanPeriode' => $totalHargaPenjualanPeriode,
            'totalKuantitasPenjualanPeriode' => $totalKuantitasPenjualanPeriode,
            'periodeAktif' => $periodeAktif,
            'keteranganPeriode' => $keteranganPeriode,
            'penjualanPadaPeriodePaginate' => $penjualanPadaPeriodePaginate->appends(['periode' => $periodeAktif]),
            'monthlySalesData' => $monthlySalesData,
        ]);
    }
}
