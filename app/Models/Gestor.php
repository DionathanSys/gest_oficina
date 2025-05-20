<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gestor extends Model
{
    use HasFactory;

    protected $table = 'gestores';

    public function indicadores()
    {
        return $this->hasMany(Indicador::class, 'gestor_id');
    }

    public function resultadoIndicador()
    {
        return $this->hasMany(ResultadoIndicador::class, 'gestor_id');
    }
}
