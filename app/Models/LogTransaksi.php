<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogTransaksi extends Model
{
    use HasFactory;

    protected $table = 'log_transaksi';

   protected $fillable = [
        'tanggal',
        'tipe_item',
        'item_id',
        'tipe_transaksi',
        'jumlah',
        'catatan',
    ];
    

     protected $casts = [
        'jumlah' => 'decimal:2',
    ];

    // Definisikan relasi jika perlu (untuk menampilkan nama item di view log)
    public function produkJadi()
    {
        return $this->belongsTo(ProdukJadi::class, 'item_id');
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'item_id'); // Jika Anda juga melog bahan baku
    }
}
