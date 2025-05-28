<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;

class SalesController extends Controller
{
    public function index()
    {
        $penjualan = Penjualan::all();
        return view('sales.index', ['penjualan' => $penjualan]);
    }
}
