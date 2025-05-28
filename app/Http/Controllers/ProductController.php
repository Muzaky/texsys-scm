<?php

namespace App\Http\Controllers;

use App\Models\ProdukJadi;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $product = ProdukJadi::all();
        return view('productstock.index', ['product' => $product]);
    }
}
