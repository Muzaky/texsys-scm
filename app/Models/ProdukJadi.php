<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProdukJadi extends Model
{
    use HasFactory;

    
    protected $table = 'produk_jadi';

   
    protected $fillable = [
        'kategori',
        'stok_level',
        'harga',
    ];

    
    protected $casts = [
        'harga' => 'decimal:2',
        'stok_level' => 'integer',
    ];

    
    public function resepProduk(): HasMany
    {
      
        return $this->hasMany(ResepProduk::class, 'produk_jadi_id');
    }

   
    public function penjualan(): HasMany
    {
       
        return $this->hasMany(Penjualan::class, 'produk_jadi_id');
    }
}
