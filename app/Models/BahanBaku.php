<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BahanBaku extends Model
{
     use HasFactory;

    
    protected $table = 'bahan_baku';

   
    protected $fillable = [
        'nama',
        'satuan',
        'stok_level',
        'harga',
    ];

    
    protected $casts = [
        'stok_level' => 'decimal:2',
        'harga' => 'decimal:2',
    ];

   
    public function resepProduk(): HasMany
    {
       
        return $this->hasMany(ResepProduk::class, 'bahan_baku_id');
    }

    public function bahanBaku()
{
    return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
}
}
