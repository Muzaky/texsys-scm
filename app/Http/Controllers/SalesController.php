<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;

class SalesController extends Controller
{
    public function index()
    {
        $penjualan = Penjualan::select('id','tanggal_penjualan', 'produk_jadi_id', 'jumlah_terjual', 'total_harga')
        ->with('produkJadi:id,kategori')->get();
        
        return view('sales.index', ['penjualan' => $penjualan]);
    }
}
