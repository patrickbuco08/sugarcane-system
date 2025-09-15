<?php

namespace Bocum\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    use HasFactory;

    protected $fillable = [
        'harvest_batch_id',
        'label',
        'avg_brix',
        'pol',
        'ch_r',
        'ch_s',
        'ch_t',
        'ch_u',
        'ch_v',
        'ch_w',
        'sensor_temp_c',
        'model_version',
        'coeff_hash',
    ];

    protected $casts = [
        'avg_brix'      => 'decimal:3',
        'pol'           => 'decimal:3',
        'sensor_temp_c' => 'decimal:2',
    ];

    // Relationships
    public function harvestBatch()
    {
        return $this->belongsTo(HarvestBatch::class);
    }

    // Optional per-sample LKGTC (for charts/QA)
    public function lkgTcSample(): ?float
    {
        $batch = $this->harvestBatch;
        if (!$batch) return null;
        return (float) $this->avg_brix * (float) $batch->recovery_coeff;
    }

    public function lkgtc(): ?float
    {
        $recoveryCoefficient = 0.9;
        return $this->avg_brix * $recoveryCoefficient;
    }

    public function lkg(): ?float
    {
        $lkgtc = $this->lkgtc();
        if ($lkgtc === null) return null;
        return (float) $lkgtc * (float) $this->harvestBatch->tons_harvested;
    }

    public function profit(): ?float
    {
        // Check if required data is available
        if (!$this->harvestBatch || 
            $this->harvestBatch->tons_harvested === null || 
            !$this->harvestBatch->weeklyPrice ||
            $this->harvestBatch->farmers_share === null) {
            return null;
        }
        
        $lkg = $this->lkg();
        if ($lkg === null) return null;
        
        $farmerShare = $this->harvestBatch->farmers_share;
        $biddingPrice = $this->harvestBatch->weeklyPrice->b_domestic;
        
        return $this->harvestBatch->tons_harvested * $lkg * $farmerShare * $biddingPrice;
    }
}
