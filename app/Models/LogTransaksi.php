<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogTransaksi extends Model
{
    use HasFactory;

    protected $table = 'log_transaksi';

   protected $fillable = [
        'tipe_item',
        'tipe_transaksi',
        'jumlah',
    ];

     protected $casts = [
        'jumlah' => 'decimal:2',
    ];
}
