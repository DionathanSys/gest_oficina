<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotoristaViagem extends Model
{
    use HasFactory;

    protected $table = 'motoristas_viagem';

    protected $casts = [
        'frete' => MoneyCast::class,
        'comissao' => MoneyCast::class,
        'vlr_comissao' => MoneyCast::class,
    ];
}
