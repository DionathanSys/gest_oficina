<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Indicador extends Model
{
    use HasFactory;

    protected $table = 'indicadores';

    public function gestor(): BelongsTo
    {
        return $this->belongsTo(Gestor::class, 'gestor_id');
    }

    public function gestores(): BelongsToMany
    {
        return $this->belongsToMany(Gestor::class, 'gestor_indicador', 'indicador_id', 'gestor_id');
    }

    public function resultadoIndicador(): HasMany
    {
        return $this->hasMany(ResultadoIndicador::class, 'indicador_id');
    }
}
