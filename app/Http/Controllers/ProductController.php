<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\ProdukJadi;
use App\Models\LogTransaksi;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $product = ProdukJadi::all();
        $productquery = ProdukJadi::query();
        $searchQuery = $request->input('search');
        if ($searchQuery) {
            $productquery->where(function ($q) use ($searchQuery) {
                $q->where('kategori', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('id', 'LIKE', "%{$searchQuery}%");

                if (preg_match('/^TDX(\d+)$/i', $searchQuery, $matches)) {
                    $q->orWhere('id', $matches[1]);
                }
            });
        }


        $filterStokLebihBesar50 = $request->input('filter_stok_lebih_besar_50') == '1';
        $filterHargaLebihBesar500k = $request->input('filter_harga_lebih_besar_1000000') == '1';

        if ($filterStokLebihBesar50) {
            $productquery->where('stok_level', '>', 50);
        }

        if ($filterHargaLebihBesar500k) {
            $productquery->where('harga', '<', 300000);
        }

        $product = $productquery->orderBy('id', 'asc')->paginate(10);
        $allProdukJadiForDropdown = ProdukJadi::select('id', 'kategori', 'stok_level', 'harga')
            ->orderBy('kategori')
            ->get();

        return view('productstock.index', [
            'product' => $product,
            'searchQuery' => $searchQuery,
            'filterStokLebihBesar50' => $filterStokLebihBesar50,
            'filterHargaLebihBesar500k' => $filterHargaLebihBesar500k,
            'allProdukJadiForDropdown' => $allProdukJadiForDropdown,
        ]);
    }

    public function findById(Request $request)
    {
        $kodeItemInput = $request->input('kode_item');
        $itemId = null;


        if (preg_match('/^(?:TDP|BBK)(\d+)$/i', $kodeItemInput, $matches)) {
            $itemId = (int)$matches[1];
        } elseif (is_numeric($kodeItemInput)) {
            $itemId = (int)$kodeItemInput;
        }

        if (!$itemId) {
            return response()->json(['success' => false, 'message' => 'Format Kode Item tidak valid.']);
        }

        $produkJadi = ProdukJadi::find($itemId); 

        if ($produkJadi) {
            return response()->json([
                'success' => true,
                'id' => $produkJadi->id,
                'kategori' => $produkJadi->kategori, 
                'satuan' => $produkJadi->satuan, 
                'stok_sekarang' => $produkJadi->stok_level 
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Bahan baku tidak ditemukan.']);
        }
    }

    public function addStock(Request $request)
    {
        // Validasi input utama
        if (!$request->filled('produk_jadi_id_hidden') || !$request->filled('jumlah_stok') || !is_numeric($request->jumlah_stok) || (float)$request->jumlah_stok <= 0) {
            Log::warning('Add Stock Validation Failed: Input tidak valid.', ['request_data' => $request->all()]);
            return redirect()->back()
                ->with('error', 'Input tidak valid. Pastikan produk dipilih dan jumlah adalah angka positif.')
                ->withInput()
                ->with('error_modal', 'addProdukStockModal');
        }

        DB::beginTransaction();
        try {
            $produkJadiId = $request->produk_jadi_id_hidden;
            $jumlahProdukJadiDitambah = (float)$request->jumlah_stok; 
            $catatanOperasi = $request->catatan_stok ?? 'Penambahan stok produk jadi (hasil produksi).';

            Log::info("Attempting to add stock for ProdukJadi ID: {$produkJadiId}, Jumlah Produksi: {$jumlahProdukJadiDitambah}");

            $produkJadi = ProdukJadi::with('resepProduk.bahanBaku')->find($produkJadiId); 

            if (!$produkJadi) {
                DB::rollBack();
                Log::warning("ProdukJadi not found for ID: {$produkJadiId} during add stock.");
                return redirect()->back()->with('error', 'Produk Jadi tidak ditemukan.')->withInput()->with('error_modal', 'addProdukStockModal');
            }

         
            if ($produkJadi->resepProduk && $produkJadi->resepProduk->count() > 0) {
                foreach ($produkJadi->resepProduk as $itemResep) {
                    $bahanBaku = $itemResep->bahanBaku; 
                    if (!$bahanBaku) {
                        DB::rollBack();
                        Log::error("Bahan Baku dengan ID: {$itemResep->bahan_baku_id} pada resep Produk Jadi ID: {$produkJadi->id} tidak ditemukan.");
                        return redirect()->back()->with('error', "Detail bahan baku pada resep tidak ditemukan (ID: {$itemResep->bahan_baku_id}). Proses dibatalkan.")->withInput()->with('error_modal', 'addProdukStockModal');
                    }

                    $jumlahBahanDibutuhkanTotal = $itemResep->jumlah_dibutuhkan * $jumlahProdukJadiDitambah;
                    Log::info("Processing Bahan Baku ID: {$bahanBaku->id} ({$bahanBaku->nama}). Dibutuhkan: {$jumlahBahanDibutuhkanTotal} {$bahanBaku->satuan}. Stok saat ini: {$bahanBaku->stok_level}");

                    if ($bahanBaku->stok_level < $jumlahBahanDibutuhkanTotal) {
                        DB::rollBack();
                        Log::warning("Insufficient stock for Bahan Baku ID: {$bahanBaku->id} ({$bahanBaku->nama}). Needed: {$jumlahBahanDibutuhkanTotal}, Available: {$bahanBaku->stok_level}");
                        return redirect()->back()->with('error', "Stok bahan baku '{$bahanBaku->nama}' tidak mencukupi (dibutuhkan: {$jumlahBahanDibutuhkanTotal} {$bahanBaku->satuan}, tersedia: {$bahanBaku->stok_level}). Proses dibatalkan.")->withInput()->with('error_modal', 'addProdukStockModal');
                    }

                    $bahanBaku->stok_level -= $jumlahBahanDibutuhkanTotal;
                    $bahanBaku->save();
                    Log::info("Bahan Baku ID: {$bahanBaku->id} stock updated. New stock: {$bahanBaku->stok_level}");

                    // Catat pengurangan stok bahan baku ke log transaksi
                    if (class_exists(LogTransaksi::class)) {
                        LogTransaksi::create([
                            'tanggal' => now()->toDateString(),
                            'tipe_item' => 'bahan_baku',
                            'item_id' => $bahanBaku->id,
                            'tipe_transaksi' => 'PENGGUNAAN_PRODUKSI',
                            'jumlah' => $jumlahBahanDibutuhkanTotal, // Jumlah yang digunakan (positif)
                            'catatan' => "Digunakan untuk produksi {$jumlahProdukJadiDitambah} unit Produk Jadi: {$produkJadi->kategori} (ID: TDP{$produkJadi->id})",
                        ]);
                    }
                }
            } else {
                Log::info("ProdukJadi ID: {$produkJadi->id} tidak memiliki resep. Hanya stok produk jadi yang akan ditambahkan.");
               
            }

           
            $produkJadi->stok_level += $jumlahProdukJadiDitambah;
            $produkJadi->save();
            Log::info("ProdukJadi ID: {$produkJadi->id} stock updated (added). New stock: {$produkJadi->stok_level}");

            
            if (class_exists(LogTransaksi::class)) {
                $logEntry = LogTransaksi::create([
                    'tanggal' => now()->toDateString(),
                    'tipe_item' => 'produk_jadi',
                    'item_id' => $produkJadi->id,
                    'tipe_transaksi' => 'HASIL_PRODUKSI',
                    'jumlah' => $jumlahProdukJadiDitambah,
                    'catatan' => $catatanOperasi,
                ]);
                if ($logEntry && $logEntry->id) {
                    Log::info("LogTransaksi (ProdukJadi addition) created successfully. Log ID: {$logEntry->id}");
                } else {
                    Log::error("Failed to create LogTransaksi for ProdukJadi addition.", ['data_sent' => $request->all()]);
                }
            }

            DB::commit();
            Log::info("Stock addition and material consumption committed for ProdukJadi ID: {$produkJadi->id}");
            return redirect()->route('productstock')
                ->with('success', 'Stok produk jadi berhasil ditambahkan dan stok bahan baku telah disesuaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat proses produksi (addStock ProdukJadi): ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString(), ['request_data' => $request->all()]);
            return redirect()->back()
                ->with('error', 'Gagal memproses produksi. Terjadi kesalahan internal. Silakan cek log.')
                ->withInput()
                ->with('error_modal', 'addProdukStockModal');
        }
    }

    
    public function reduceStock(Request $request)
    {
        
        if (!$request->filled('produk_jadi_id_hidden') || !$request->filled('jumlah_stok') || !is_numeric($request->jumlah_stok) || (float)$request->jumlah_stok <= 0) {
            Log::warning('Reduce Stock Validation Failed: Input tidak valid.', ['request_data' => $request->all()]);
            return redirect()->back()
                ->with('error', 'Input tidak valid. Pastikan produk dipilih dan jumlah pengurangan adalah angka positif.')
                ->withInput()
                ->with('error_modal', 'addProdukStockModal'); 
        }

        DB::beginTransaction();
        try {
            $produkJadiId = $request->produk_jadi_id_hidden;
            $jumlahStokOperasi = (float)$request->jumlah_stok; 
            $catatan = $request->catatan_stok ?? 'Pengurangan stok produk jadi manual.';

            Log::info("Attempting to reduce stock for ProdukJadi ID: {$produkJadiId}, Jumlah Pengurangan: {$jumlahStokOperasi}");

            $produkJadi = ProdukJadi::find($produkJadiId);

            if (!$produkJadi) {
                DB::rollBack();
                Log::warning("ProdukJadi not found for ID: {$produkJadiId} during reduce stock.");
                return redirect()->back()
                    ->with('error', 'Produk Jadi tidak ditemukan saat proses simpan.')
                    ->withInput()
                    ->with('error_modal', 'addProdukStockModal');
            }

            // Validasi apakah stok mencukupi
            if ($produkJadi->stok_level < $jumlahStokOperasi) {
                DB::rollBack();
                Log::warning("Insufficient stock for ProdukJadi ID: {$produkJadiId}. Current: {$produkJadi->stok_level}, Attempted to reduce by: {$jumlahStokOperasi}");
                return redirect()->back()
                    ->with('error', "Stok produk '{$produkJadi->kategori}' tidak mencukupi (Stok saat ini: {$produkJadi->stok_level}).")
                    ->withInput()
                    ->with('error_modal', 'addProdukStockModal');
            }

            $produkJadi->stok_level -= $jumlahStokOperasi;
            $produkJadi->save();
            Log::info("ProdukJadi ID: {$produkJadi->id} stock reduced. New stock: {$produkJadi->stok_level}");

            if (class_exists(LogTransaksi::class)) {
                $logEntry = LogTransaksi::create([
                    'tanggal' => now()->toDateString(),
                    'tipe_item' => 'produk_jadi',
                    'item_id' => $produkJadi->id,
                    'tipe_transaksi' => 'PENGURANGAN_STOK_PRODUK', 
                    'jumlah' => $jumlahStokOperasi, 
                    'catatan' => $catatan,
                ]);

                if ($logEntry && $logEntry->id) {
                    Log::info("LogTransaksi (reduction) created successfully for ProdukJadi ID: {$produkJadi->id}, Log ID: {$logEntry->id}");
                } else {
                    Log::error("Failed to create LogTransaksi (reduction) for ProdukJadi ID: {$produkJadi->id}.", ['data_sent' => $request->all()]);
                }
            } else {
                Log::warning('Model LogTransaksi class does not exist, skipping log creation for ProdukJadi ID: ' . $produkJadi->id);
            }

            DB::commit();
            Log::info("Stock reduction committed for ProdukJadi ID: {$produkJadi->id}");
            return redirect()->route('productstock')
                ->with('success', 'Stok berhasil dikurangi untuk produk: ' . $produkJadi->kategori . '.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat mengurangi stok produk jadi: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString(), ['request_data' => $request->all()]);
            return redirect()->back()
                ->with('error', 'Gagal mengurangi stok. Terjadi kesalahan internal. Silakan cek log.')
                ->withInput()
                ->with('error_modal', 'addProdukStockModal');
        }
    }
}
