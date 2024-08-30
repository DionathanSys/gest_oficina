<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViagemAgro extends Model
{
    use HasFactory;

    protected $casts = [
        'frete' => MoneyCast::class,
        'vlr_cte' => MoneyCast::class,
        'vlr_nfs' => MoneyCast::class,
    ];

    protected $table = 'viagens_agro';
}
