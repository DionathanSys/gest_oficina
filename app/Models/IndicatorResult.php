<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class IndicatorResult extends Model
{
    use HasFactory;

    protected $table = 'indicator_result';

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Indicator::class);
    }

    public function managers(): BelongsToMany
    {
        return $this->belongsToMany(Manager::class, 'manager_indicator_result', 'indicator_result_id', 'manager_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Manager::class);
    }
}
