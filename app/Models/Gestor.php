<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Gestor extends Model
{
    use HasFactory;

    protected $table = 'gestores';

    public function indicadors()
    {
        return $this->belongsToMany(Indicador::class, 'gestor_indicador', 'gestor_id', 'indicador_id');
    }

    public function resultadoIndicador()
    {
        return $this->hasMany(ResultadoIndicador::class, 'gestor_id');
    }
}
