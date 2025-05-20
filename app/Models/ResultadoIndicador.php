<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultadoIndicador extends Model
{
    use HasFactory;

    protected $table = 'resultados_indicador';

    public function gestor()
    {
        return $this->belongsTo(Gestor::class, 'gestor_id');
    }
    public function indicador()
    {
        return $this->belongsTo(Indicador::class, 'indicador_id');
    }
}
