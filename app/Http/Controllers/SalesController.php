<?php

namespace App\Http\Controllers;

use App\Models\ProdukJadi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\LogTransaksi;

use Illuminate\Http\Request;
use App\Models\Penjualan;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $penjualanQuery = Penjualan::select('id', 'tanggal_penjualan', 'produk_jadi_id', 'jumlah_terjual', 'total_harga')
            ->with('produkJadi:id,kategori')
            ->orderBy('tanggal_penjualan', 'desc')
            ->orderBy('id', 'desc');


        if ($request->filled('search_sales')) {
            $searchTerm = $request->search_sales;
            $penjualanQuery->where(function ($query) use ($searchTerm) {

                $idSearchTerm = $searchTerm;
                if (is_numeric($searchTerm)) {
                    $idSearchTerm = $searchTerm;
                } elseif (strtoupper(substr($searchTerm, 0, 3)) === 'TRF' && is_numeric(substr($searchTerm, 3))) {
                    $idSearchTerm = substr($searchTerm, 3);
                }

                $query->where('id', 'LIKE', "%{$idSearchTerm}%")
                    ->orWhereHas('produkJadi', function ($q) use ($searchTerm) {
                        $q->where('kategori', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }

        $penjualan = $penjualanQuery->paginate(15);


        $allProdukJadiForDropdown = ProdukJadi::select('id', 'kategori', 'harga', 'stok_level')
            ->orderBy('kategori')
            ->get();

        return view('sales.index', [
            'penjualan' => $penjualan,
            'allProdukJadiForDropdown' => $allProdukJadiForDropdown,
            'searchQuery' => $request->input('search_sales', '')
        ]);
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'tanggal_penjualan' => 'required|date|before_or_equal:today',
            'produk_jadi_id' => 'required|exists:produk_jadi,id',
            'jumlah_terjual' => 'required|integer|min:1',
            'total_harga' => 'required|numeric|min:0',
            'catatan_penjualan' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {

            $produkJadi = ProdukJadi::find($validatedData['produk_jadi_id']);

            if (!$produkJadi) {
                DB::rollBack();

                return redirect()->back()->with('error', 'Produk Jadi tidak ditemukan.')->withInput();
            }


            if ($produkJadi->stok_level < $validatedData['jumlah_terjual']) { //
                DB::rollBack();
                return redirect()->back()
                    ->with('error', "Stok produk '{$produkJadi->kategori}' tidak mencukupi (Stok saat ini: {$produkJadi->stok_level}, Jumlah dijual: {$validatedData['jumlah_terjual']}).") //
                    ->withInput();
            }


            $produkJadi->stok_level -= $validatedData['jumlah_terjual']; //
            $produkJadi->save();
            Log::info("Stok Produk Jadi ID {$produkJadi->id} dikurangi sebanyak {$validatedData['jumlah_terjual']}. Stok baru: {$produkJadi->stok_level}");


            $penjualan = Penjualan::create([
                'tanggal_penjualan' => $validatedData['tanggal_penjualan'], //
                'produk_jadi_id' => $validatedData['produk_jadi_id'], //
                'jumlah_terjual' => $validatedData['jumlah_terjual'], //
                'total_harga' => $validatedData['total_harga'], //

            ]);
            Log::info("Penjualan baru berhasil dicatat. ID Penjualan: {$penjualan->id}");


            if (class_exists(LogTransaksi::class)) {
                LogTransaksi::create([
                    'tanggal' => $validatedData['tanggal_penjualan'], //
                    'tipe_item' => 'produk_jadi', //
                    'item_id' => $produkJadi->id, //
                    'tipe_transaksi' => 'PENJUALAN', //
                    'jumlah' =>  -$validatedData['jumlah_terjual'], //
                    'catatan' => $validatedData['catatan_penjualan'] ?? "Penjualan produk {$produkJadi->kategori}, ID Penjualan: {$penjualan->id}",
                ]);
                Log::info("Log transaksi untuk penjualan ID {$penjualan->id} berhasil dicatat.");
            } else {
                Log::warning('Model LogTransaksi tidak ditemukan, logging pengurangan stok karena penjualan dilewati.');
            }

            DB::commit();
            return redirect()->route('sales')->with('success', 'Penjualan berhasil ditambahkan dan stok produk telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menyimpan penjualan atau mengurangi stok: {$e->getMessage()} \nStack Trace: {$e->getTraceAsString()}", $request->all());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan penjualan. Stok tidak berubah. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'produk_jadi_id' => 'required|exists:produk_jadi,id',
            'jumlah_terjual' => 'required|integer|min:1',
            'total_harga' => 'required|numeric|min:0',
            'catatan_penjualan' => 'nullable|string|max:255',
            'original_jumlah_terjual' => 'required|integer|min:0',
            'original_produk_jadi_id' => 'required|exists:produk_jadi,id',
        ]);

        DB::beginTransaction();

        try {
            $penjualan = Penjualan::findOrFail($id);
            $newProdukJadi = ProdukJadi::findOrFail($validatedData['produk_jadi_id']);
            $originalProdukJadi = ProdukJadi::findOrFail($validatedData['original_produk_jadi_id']);

            $catatanLog = $validatedData['catatan_penjualan'] ?? "Update penjualan TRF{$penjualan->id}";

        
            $originalProdukJadi->stok_level += $validatedData['original_jumlah_terjual'];
            $originalProdukJadi->save();
            Log::info("Stok Produk Jadi ID {$originalProdukJadi->id} dikembalikan sebanyak {$validatedData['original_jumlah_terjual']} (Edit Penjualan TRF{$id}). Stok baru: {$originalProdukJadi->stok_level}");

            if (class_exists(LogTransaksi::class)) {
                LogTransaksi::create([
                    'tanggal' => now(), 
                    'tipe_item' => 'produk_jadi',
                    'item_id' => $originalProdukJadi->id,
                    'tipe_transaksi' => 'PENGEMBALIAN STOK (EDIT PENJUALAN)',
                    'jumlah' => -$validatedData['original_jumlah_terjual'], 
                    'catatan' => "Stok dikembalikan dari {$originalProdukJadi->kategori} karena edit penjualan TRF{$penjualan->id}. {$catatanLog}",
                ]);
            }

       
            $targetProdukForNewSale = ($originalProdukJadi->id == $newProdukJadi->id) ? $originalProdukJadi : $newProdukJadi;

          
            if ($originalProdukJadi->id != $newProdukJadi->id) {
                $targetProdukForNewSale->refresh(); 
            }


            if ($targetProdukForNewSale->stok_level < $validatedData['jumlah_terjual']) {
                DB::rollBack(); 
                return redirect()->back()
                    ->with('error', "Stok produk '{$targetProdukForNewSale->kategori}' tidak mencukupi untuk jumlah baru (Stok tersedia setelah potensi pengembalian: {$targetProdukForNewSale->stok_level}, Jumlah dijual: {$validatedData['jumlah_terjual']}).")
                    ->withInput()
                    ->with('error_modal_sales', 'edit'); 
            }

            $targetProdukForNewSale->stok_level -= $validatedData['jumlah_terjual'];
            $targetProdukForNewSale->save();
            Log::info("Stok Produk Jadi ID {$targetProdukForNewSale->id} dikurangi sebanyak {$validatedData['jumlah_terjual']} (Edit Penjualan TRF{$id}). Stok baru: {$targetProdukForNewSale->stok_level}");


            if (class_exists(LogTransaksi::class)) {
                LogTransaksi::create([
                    'tanggal' => $penjualan->tanggal_penjualan,
                    'tipe_item' => 'produk_jadi',
                    'item_id' => $targetProdukForNewSale->id,
                    'tipe_transaksi' => 'PENJUALAN (EDIT)',
                    'jumlah' => $validatedData['jumlah_terjual'], 
                    'catatan' => $validatedData['catatan_penjualan'],
                ]);
            }

   

            $penjualan->produk_jadi_id = $validatedData['produk_jadi_id'];
            $penjualan->jumlah_terjual = $validatedData['jumlah_terjual'];
            $penjualan->total_harga = $validatedData['total_harga'];
 
            $penjualan->save();
            Log::info("Penjualan TRF{$id} berhasil diperbarui.");

            DB::commit();
            return redirect()->route('sales')->with('success', 'Penjualan berhasil diperbarui dan stok produk telah disesuaikan.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::error("Gagal update penjualan TRF{$id}: Data tidak ditemukan. {$e->getMessage()}");
            return redirect()->route('sales')->with('error', 'Data penjualan atau produk tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal update penjualan TRF{$id}: {$e->getMessage()} \nStack Trace: {$e->getTraceAsString()}", $request->all());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui penjualan. Perubahan stok dibatalkan. Silakan coba lagi.')
                ->withInput()
                ->with('error_modal_sales', 'edit'); // Ensure edit modal reopens
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $penjualan = Penjualan::findOrFail($id);
            $produkJadi = ProdukJadi::find($penjualan->produk_jadi_id);

            if ($produkJadi) {
                $produkJadi->stok_level += $penjualan->jumlah_terjual;
                $produkJadi->save();
                Log::info("Stok Produk Jadi ID {$produkJadi->id} ({$produkJadi->kategori}) dikembalikan sebanyak {$penjualan->jumlah_terjual} karena penghapusan Penjualan TRF{$id}. Stok baru: {$produkJadi->stok_level}");

                if (class_exists(LogTransaksi::class)) {
                    LogTransaksi::create([
                        'tanggal' => now(),
                        'tipe_item' => 'produk_jadi',
                        'item_id' => $produkJadi->id,
                        'tipe_transaksi' => 'PENGEMBALIAN STOK (HAPUS PENJUALAN)',
                        'jumlah' => $penjualan->jumlah_terjual,
                        'catatan' => "Stok dikembalikan untuk produk {$produkJadi->kategori} karena penghapusan penjualan TRF{$penjualan->id}. {$penjualan->catatan_penjualan}",
                    ]);
                    Log::info("Log transaksi untuk pengembalian stok penjualan TRF{$id} berhasil dicatat.");
                }
            } else {
                Log::warning("Produk Jadi dengan ID {$penjualan->produk_jadi_id} tidak ditemukan saat menghapus Penjualan TRF{$id}. Stok tidak dapat dikembalikan.");
               
            }

            $penjualan->delete();
            Log::info("Penjualan TRF{$id} berhasil dihapus.");

            DB::commit();
            return redirect()->route('sales')->with('success', 'Penjualan berhasil dihapus dan stok produk (jika ditemukan) telah dikembalikan.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::error("Gagal hapus penjualan: Data penjualan TRF{$id} tidak ditemukan. {$e->getMessage()}");
            return redirect()->route('sales')->with('error', 'Data penjualan tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menghapus penjualan TRF{$id}: {$e->getMessage()} \nStack Trace: {$e->getTraceAsString()}");
            return redirect()->route('sales')->with('error', 'Terjadi kesalahan saat menghapus penjualan. Stok tidak berubah. Silakan coba lagi.');
        }
    }
}
