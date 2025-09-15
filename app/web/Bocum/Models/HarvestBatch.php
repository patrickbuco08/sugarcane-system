<?php

namespace Bocum\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HarvestBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'week_of',
        'weekly_price_id',
        'tons_harvested',
        'recovery_coeff',
        'farmers_share',
    ];

    protected $casts = [
        'week_of'        => 'date',
        'tons_harvested' => 'decimal:3',
        'recovery_coeff' => 'decimal:4',
        'farmers_share'  => 'decimal:4',
    ];

    // Relationships
    public function weeklyPrice()
    {
        return $this->belongsTo(WeeklyPrice::class);
    }

    public function samples()
    {
        return $this->hasMany(Sample::class);
    }

    // ---------- Computation helpers (no persistence) ----------
    public function avgBrix(): ?float
    {
        // returns null if no samples yet
        $val = $this->samples()->avg('avg_brix');
        return is_null($val) ? null : (float)$val;
    }

    public function lkgTc(): ?float
    {
        $avg = $this->avgBrix();
        if ($avg === null) return null;
        return (float) $avg * (float) $this->recovery_coeff;
    }

    public function lkg(): ?float
    {
        $lkgTc = $this->lkgTc();
        if ($lkgTc === null) return null;
        return (float) $lkgTc * (float) $this->tons_harvested;
    }

    public function profit(): ?float
    {
        $lkg = $this->lkg();
        if ($lkg === null) return null;

        $price = optional($this->weeklyPrice)->b_domestic;
        if ($price === null) return null;

        return (float) $this->tons_harvested
            * (float) $lkg
            * (float) $this->farmers_share
            * (float) $price;
    }

    // Convenient “computed summary” accessor
    public function computed(): array
    {
        return [
            'avg_brix_batch' => $this->avgBrix(),
            'lkg_tc'         => $this->lkgTc(),
            'lkg'            => $this->lkg(),
            'profit'         => $this->profit(),
            'price'          => optional($this->weeklyPrice)->b_domestic,
        ];
    }
}
