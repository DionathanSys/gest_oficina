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
        // 'vlr_comissao' => MoneyCast::class, //! vlr_comissão = frete * (%) comissao -> 1000 * 6,75%
    ];

    public function acerto()
    {
        return $this->belongsTo(Acerto::class, 'motorista_id','motorista_id')->where('fechamento', $this->fechamento);
    }

    public function acerto_dupla()
    {
        return $this->belongsTo(Acerto::class, 'motorista_id','motorista_dupla_id')->where('fechamento', $this->fechamento);
    }

    public function viagem()
    {
        return $this->belongsTo(ViagemAgro::class, 'viagem_agro_id','id');
    }
}
