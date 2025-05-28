<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Penjualan;
use App\Models\ProdukJadi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();
        $bahanbaku = BahanBaku::select('nama','stok_level','satuan','harga')->get();
        $produkjadi = ProdukJadi::select('kategori','stok_level','harga')->get();
        $penjualan = Penjualan::select('tanggal_penjualan','produk_jadi_id', 'jumlah_terjual', 'total_harga')
                                    ->with('produkJadi:id,kategori')
                                    ->get();

       $totalNilaiBahanBaku = $bahanbaku->sum(function ($item) {
            return $item->stok_level * $item->harga;
        });

        $totalNilaiProdukJadi = $produkjadi->sum(function ($item) {
            return $item->stok_level * $item->harga;
        });

        $periodeAktif = $request->input('periode', 'semua');
        $now = Carbon::now();
        
        $queryPenjualan = Penjualan::query()->with('produkJadi'); 
                                                                

        $startDate = null;
        $keteranganPeriode = "Semua Periode";

        if ($periodeAktif === '1M') {
            $startDate = $now->copy()->subMonth()->startOfDay();
            $keteranganPeriode = "1 Bulan Terakhir";
        } elseif ($periodeAktif === '3M') {
            $startDate = $now->copy()->subMonths(3)->startOfDay();
            $keteranganPeriode = "3 Bulan Terakhir";
        }
        
        if ($startDate) {
            $queryPenjualan->where('tanggal_penjualan', '>=', $startDate) 
                           ->where('tanggal_penjualan', '<=', $now->copy()->endOfDay());
        }
        
       
        $penjualanPadaPeriode = $queryPenjualan->orderBy('tanggal_penjualan', 'desc')->get();
        $penjualanPadaPeriodePaginate = $queryPenjualan->orderBy('tanggal_penjualan', 'desc')->paginate(10);


        $totalHargaPenjualanPeriode = $penjualanPadaPeriode->sum('total_harga');
        $totalKuantitasPenjualanPeriode = $penjualanPadaPeriode->sum('jumlah_terjual'); 

        $bahanBakuTeratas = BahanBaku::select('id','nama', 'stok_level', 'harga', 'satuan')
                                     ->orderBy('stok_level', 'desc') 
                                     ->take(3) 
                                     ->get();

        return view('dashboard.dashboard', [
            'bahanBaku' => $bahanbaku,
            'produkJadi' => $produkjadi,
            'totalNilaiBahanBaku' => $totalNilaiBahanBaku,
            'totalNilaiProdukJadi' => $totalNilaiProdukJadi,
            'bahanBakuTeratas' => $bahanBakuTeratas,
            'penjualan' => $penjualan,
            'totalHargaPenjualanPeriode' => $totalHargaPenjualanPeriode,
            'totalKuantitasPenjualanPeriode' => $totalKuantitasPenjualanPeriode,
            'periodeAktif' => $periodeAktif,
            'keteranganPeriode' => $keteranganPeriode,
            'penjualanPadaPeriode' => $penjualanPadaPeriode,
            'penjualanPadaPeriodePaginate' => $penjualanPadaPeriodePaginate,
        ]);
    }
}
