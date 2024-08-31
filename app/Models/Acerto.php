<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acerto extends Model
{
    use HasFactory;

    protected $casts = [
        'vlr_fechamento' => MoneyCast::class,
        'vlr_media' => MoneyCast::class,
        'vlr_inss' => MoneyCast::class,
        'vlr_irrf' => MoneyCast::class,
        'vlr_manutencao' => MoneyCast::class,
        'vlr_diferenca' => MoneyCast::class,
        'vlr_comissao' => MoneyCast::class,
    ];
    
    public function motorista()
    {
        return $this->belongsTo(Motorista::class);
    }

    public function PrSeguranca()
    {
        return $this->hasOne(PrSeguranca::class);
    }

    public function getSalarioLiquido()
    {
        /**
         * Salário líquido
         * Fechamento + Média + Manutenção + Produtividade + Pr. Seg. - Impostos
         * Impostos = INSS + IRRF
         * Produtividade = Impostos - Diferença
         */

        $prSeguranca = $this->PrSeguranca->premio ?? 0;

        $calculo = $this->vlr_fechamento + $this->vlr_media + $this->vlr_manutencao + $prSeguranca;
        $calculo = $calculo - $this->vlr_diferenca;
        return $calculo;
    }
}
