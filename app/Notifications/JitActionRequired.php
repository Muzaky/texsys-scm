<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\JitRecommendation;

class JitActionRequired extends Notification
{
    use Queueable;

    protected $recommendation;

    public function __construct(JitRecommendation $recommendation)
    {
        $this->recommendation = $recommendation;
    }

    public function via(object $notifiable): array
    {
        // Kita akan menggunakan 'database' untuk notifikasi di dalam web
        return ['database']; 
    }
    
    public function toArray(object $notifiable): array
    {
        $item = $this->recommendation->item; // Mengambil item (ProdukJadi atau BahanBaku)
        $itemName = $item->kategori ?? $item->nama; // Mengambil nama/kategori
        $quantity = $this->recommendation->recommended_quantity;
        $unit = $item->satuan ?? 'unit'; // Mengambil satuan

        if ($this->recommendation->recommendation_type === 'PRODUKSI') {
            $message = "Segera lakukan produksi untuk '{$itemName}' sebanyak {$quantity} {$unit}.";
        } else {
            $message = "Segera lakukan pembelian '{$itemName}' sebanyak {$quantity} {$unit}.";
        }

        return [
            'message' => $message,
            'recommendation_id' => $this->recommendation->id,
            'url' => route('jit.index'), // URL tujuan saat notifikasi diklik
        ];
    }
}
