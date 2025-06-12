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
use App\Models\JitRecommendation;
use App\Notifications\JitActionRequired;
use Illuminate\Support\Facades\Notification;
use App\Enums\RecommendationStatus;
use App\Models\User;


class JITController extends Controller
{
    public function index()
    {
        // Ambil juga rekomendasi yang masih pending untuk ditampilkan jika perlu
        $pendingRecommendations = JitRecommendation::where('status', 'PENDING')->with('item')->get();
        return view('jit.index', ['pendingRecommendations' => $pendingRecommendations]);
    }

    public function analyze(Request $request)
    {
        $validated = $request->validate([
            'forecast_days' => 'required|integer|min:7|max:365',
            'safety_stock_pj_days' => 'required|integer|min:0|max:30',
            'safety_stock_bb_days' => 'required|integer|min:0|max:30',
        ]);


        $pythonApiUrl = env('PREDICTAPI');

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

            DB::transaction(function () use ($results) {
                $analysisDate = now();
                $userToNotify = User::first();


                if (isset($results['produk_jadi_to_make'])) {
                    foreach ($results['produk_jadi_to_make'] as $item) {
                        if ($item['quantity_to_make'] > 0) {
                            $recommendation = JitRecommendation::create([
                                'item_type' => ProdukJadi::class,
                                'item_id' => $item['produk_jadi_id'],
                                'recommendation_type' => 'PRODUKSI',
                                'recommended_quantity' => $item['quantity_to_make'],
                                'analysis_date' => $analysisDate,
                                'notes' => "Prediksi Jual: {$item['total_forecasted_sales']}, Stok Pengaman: {$item['calculated_safety_stock']}"
                            ]);

                            if ($userToNotify) {
                                Notification::send($userToNotify, new JitActionRequired($recommendation));
                            }
                        }
                    }
                }


                if (isset($results['bahan_baku_to_purchase'])) {
                    foreach ($results['bahan_baku_to_purchase'] as $item) {
                        if ($item['quantity_to_purchase'] > 0) {
                            $recommendation = JitRecommendation::create([
                                'item_type' => BahanBaku::class,
                                'item_id' => $item['bahan_baku_id'],
                                'recommendation_type' => 'PEMBELIAN',
                                'recommended_quantity' => $item['quantity_to_purchase'],
                                'analysis_date' => $analysisDate,
                                'notes' => "Total Kebutuhan: {$item['total_calculated_need_for_period']}, Stok Pengaman: {$item['calculated_safety_stock']}"
                            ]);
                            // Kirim notifikasi
                            if ($userToNotify) {
                                Notification::send($userToNotify, new JitActionRequired($recommendation));
                            }
                        }
                    }
                }
            });
            // **Selesai Menyimpan Hasil**

            $produkJadiMap = ProdukJadi::pluck('kategori', 'id');
            $bahanBakuMap = BahanBaku::pluck('nama', 'id');
            $bahanBakuSatuanMap = BahanBaku::pluck('satuan', 'id');

            $data = [
                'results' => $results,
                'input' => $validated,
                'produkJadiMap' => $produkJadiMap,
                'bahanBakuMap' => $bahanBakuMap,
                'bahanBakuSatuanMap' => $bahanBakuSatuanMap,
            ];

            if ($request->ajax()) {
                return view('jit._results', $data);
            }

            return view('jit.index', $data)->with('success', 'Analisis berhasil dijalankan.');


        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function fetchNotifications(Request $request)
    {
        // 1. Ambil semua rekomendasi yang masih menunggu tindakan (status 'PENDING')
        $pendingRecommendations = JitRecommendation::where('status', 'PENDING')->with('item')->get();

        // 2. Ubah data rekomendasi menjadi format notifikasi yang bisa dibaca frontend
        $notifications = $pendingRecommendations->map(function ($rec) {
            $itemName = $rec->item->kategori ?? $rec->item->nama ?? 'Item tidak dikenal';
            $action = ($rec->recommendation_type === 'PRODUKSI') ? 'diproduksi' : 'dibeli';
            $quantity = number_format($rec->recommended_quantity, 2);
            $unit = $rec->item->satuan ?? 'unit';

            return [
                'id' => 'rec-' . $rec->id, // ID unik untuk notifikasi
                'data' => [
                    'message' => "Perlu {$action} {$itemName} sebanyak {$quantity} {$unit}.",
                    'url' => route('jit.index') // Arahkan ke halaman JIT
                ]
            ];
        });

        // 3. Kembalikan dalam format JSON
        return response()->json($notifications);
    }
    public function acknowledge(Request $request, JitRecommendation $recommendation)
    {
        try {
            $recommendation->status = RecommendationStatus::COMPLETED; 
            $recommendation->save();
            return back()->with('success', 'Rekomendasi telah ditandai selesai.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui status rekomendasi.');
        }
    }
}
