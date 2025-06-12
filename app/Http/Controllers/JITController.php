<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdukJadi;
use App\Models\BahanBaku;
use App\Models\Penjualan;
use App\Models\ResepProduk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class JITController extends Controller
{
    public function index()
    {
        return view('jit.index');
    }

    public function analyze(Request $request)
    {
        $validated = $request->validate([
            'forecast_days' => 'required|integer|min:7|max:365',
            'safety_stock_pj_days' => 'required|integer|min:0|max:30',
            'safety_stock_bb_days' => 'required|integer|min:0|max:30',
        ]);

      
        $pythonApiUrl = 'http://127.0.0.1:5001/forecast/full_analysis';

        try {

            $response = Http::timeout(600)->get($pythonApiUrl, [
                'forecast_days' => $validated['forecast_days'],
                'history_days' => 365,
                'safety_stock_pj_days' => $validated['safety_stock_pj_days'],
                'safety_stock_bb_days' => $validated['safety_stock_bb_days'],
            ]);

            if (!$response->successful()) {
    
                return back()->with('error', 'Layanan prediksi gagal merespon. Pesan: ' . $response->body());
            }

            $results = $response->json();
            
   
            $produkJadiMap = ProdukJadi::pluck('kategori', 'id');
            $bahanBakuMap = BahanBaku::pluck('nama', 'id');
            $bahanBakuSatuanMap = BahanBaku::pluck('satuan', 'id');

           
            return view('jit.index', [
                'results' => $results,
                'input' => $validated,
                'produkJadiMap' => $produkJadiMap,
                'bahanBakuMap' => $bahanBakuMap,
                'bahanBakuSatuanMap' => $bahanBakuSatuanMap,
            ]);

        } catch (\Exception $e) {
  
            return back()->with('error', 'Gagal terhubung ke layanan prediksi. Pastikan layanan Python (app.py) sedang berjalan. Error: ' . $e->getMessage());
        }
    }
}
