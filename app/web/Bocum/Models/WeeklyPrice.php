<?php

namespace Bocum\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'week_of',
        'b_domestic',
        'a_us_quota',
        'molasses_mt',
        'source',
    ];

    protected $casts = [
        'week_of'     => 'date',
        'b_domestic'  => 'decimal:2',
        'a_us_quota'  => 'decimal:2',
        'molasses_mt' => 'decimal:2',
    ];

    // Relationships
    public function harvestBatches()
    {
        return $this->hasMany(HarvestBatch::class);
    }

    // Scopes
    public function scopeLatestWeek($q)
    {
        return $q->orderByDesc('week_of');
    }
}
