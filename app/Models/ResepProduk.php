<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResepProduk extends Model
{
    use HasFactory;

    
    protected $table = 'resep_produk';

   
    protected $fillable = [
        'produk_jadi_id',
        'bahan_baku_id',  
        'jumlah_dibutuhkan',
    ];

   
    protected $casts = [
        'jumlah_dibutuhkan' => 'decimal:2',
    ];

  
    public function produkJadi(): BelongsTo
    {
   
        return $this->belongsTo(ProdukJadi::class, 'produk_jadi_id');
    }

    
    public function bahanBaku(): BelongsTo
    {
       
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }

}
