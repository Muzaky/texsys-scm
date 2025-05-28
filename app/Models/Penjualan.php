<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penjualan extends Model
{
    use HasFactory;

   
    protected $table = 'penjualan';

    
    protected $fillable = [
        'produk_jadi_id', 
        'tanggal_penjualan',
        'jumlah_terjual',
        'total_harga',
    ];

   
    protected $casts = [
        'tanggal_penjualan' => 'date',
        'jumlah_terjual' => 'integer',
        'total_harga' => 'decimal:2',
    ];

  
    public function produkJadi(): BelongsTo
    {
        // Asumsi kolom foreign key adalah 'produk_jadi_id'
        return $this->belongsTo(ProdukJadi::class, 'produk_jadi_id');
    }
}
