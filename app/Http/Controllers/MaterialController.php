<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $material = BahanBaku::all();
        return view('materialstock.index', data:['material' => $material]);
    }
}
