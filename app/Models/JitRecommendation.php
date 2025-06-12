<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Enums\RecommendationStatus;
class JitRecommendation extends Model
{
    use HasFactory;
    protected $table = 'jit_recommendations';
    protected $fillable = [
        'item_type',
        'item_id',
        'recommendation_type',
        'recommended_quantity',
        'status',
        'analysis_date',
        'notes',
    ];

    protected $casts = [
        'status' => RecommendationStatus::class, 
    ];

    public function item(): MorphTo
    {
        return $this->morphTo();
    }
}
