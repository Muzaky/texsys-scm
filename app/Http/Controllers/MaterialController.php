<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\LogTransaksi; 
use App\Models\JitRecommendation;
use App\Enums\RecommendationStatus;

class MaterialController extends Controller
{
    public function index()
    {
        $material = BahanBaku::all();
        
        return view('materialstock.index', data:['material' => $material]);
    }


    public function addStock(Request $request, $id)
    {

        $validated = $request->validate([
            'jumlah' => 'required|numeric|min:0.01', 
            'catatan' => 'nullable|string|max:255',   
        ]);

        DB::beginTransaction();
        try {
          
            $bahanBaku = BahanBaku::findOrFail($id);
            $jumlahDitambah = (float)$validated['jumlah'];
            $catatanOperasi = $validated['catatan'] ?? "Penambahan stok bahan baku: {$bahanBaku->nama}";

          
            $bahanBaku->stok_level += $jumlahDitambah;
            $bahanBaku->save();

           
            Log::info("Stock added for BahanBaku ID: {$bahanBaku->id}. Added: {$jumlahDitambah}. New stock: {$bahanBaku->stok_level}");

     
            if (class_exists(LogTransaksi::class)) {
                LogTransaksi::create([
                    'tanggal' => now()->toDateString(),
                    'tipe_item' => 'bahan_baku',
                    'item_id' => $bahanBaku->id,
                    'tipe_transaksi' => 'PENAMBAHAN_STOK',
                    'jumlah' => $jumlahDitambah,
                    'catatan' => $catatanOperasi,
                ]);
                Log::info("LogTransaksi (BahanBaku addition) created successfully for ID: {$bahanBaku->id}.");
            }

            $recommendation = JitRecommendation::where('item_type', BahanBaku::class)
                                               ->where('item_id', $id)
                                               ->where('status', 'PENDING')
                                               ->first();

            if ($recommendation) {
                
                if ($jumlahDitambah >= $recommendation->recommended_quantity) {
                    
                    $recommendation->status = RecommendationStatus::COMPLETED;
                } else {
               
                    $recommendation->recommended_quantity -= $jumlahDitambah;
                }
                $recommendation->save();
            }

         
            DB::commit();
            return redirect()->back()->with('success', "Stok untuk '{$bahanBaku->nama}' berhasil ditambahkan.");

        } catch (\Exception $e) {
            
            DB::rollBack();
            Log::error('Error saat menambah stok bahan baku: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString(), ['request_data' => $request->all()]);
            
            return redirect()->back()
                ->with('error', 'Gagal menambah stok. Terjadi kesalahan internal.')
                ->withInput();
        }
    }

}
