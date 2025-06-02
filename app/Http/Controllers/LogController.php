<?php

namespace App\Http\Controllers;
use App\Models\LogTransaction;
use App\Models\LogTransaksi;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logtransactions = LogTransaksi::orderBy('id', 'desc')
            ->paginate(20);
        return view('transactionlogs.index', ['logtransactions' => $logtransactions]);
    }
}
