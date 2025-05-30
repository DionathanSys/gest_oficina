<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manager extends Model
{
    use HasFactory;

    public function indicators(): BelongsToMany
    {
        return $this->belongsToMany(Indicator::class);
    }

    public function indicatorResults(): HasMany
    {
        return $this->hasMany(IndicatorResult::class);
    }

    public function pontuacaoTotal(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->indicatorResults->sum('pontuacao_obtida');
            }
        );
    }

    public function pontuacaoIndividual(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->indicatorResults()
                    ->whereHas('indicator', function ($query) {
                        $query->where('tipo', 'INDIVIDUAL');
                    })
                    ->sum('pontuacao_obtida');
            }
        );
    }

    public function pontuacaoColetiva(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->indicatorResults()
                    ->whereHas('indicator', function ($query) {
                        $query->where('tipo', 'COLETIVO');
                    })
                    ->sum('pontuacao_obtida');
            }
        );
    }




}
