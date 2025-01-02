<?php

namespace App\Actions;

use App\Enums\StatusCotacaoEnum;
use App\Models\PropostaCotacao;

class AprovarPropostaAction
{
    public static function exec(PropostaCotacao $propostaCotacao)
    {
        $cotacao = $propostaCotacao->cotacao;

        $cotacao->load('produtos_cotacao', 'propostas_cotacao');

        $produtosCotacao = $cotacao->produtos_cotacao;
        
        if ($produtosCotacao->count() === 1) {

            $cotacao->propostas_cotacao->each(function ($proposta) {
                $proposta->update(['status' => StatusCotacaoEnum::REPROVADO]);
            });

            $propostaCotacao->update(['status' => StatusCotacaoEnum::APROVADO]);

            return true;
        }

        $propostaCotacao->update(['status' => StatusCotacaoEnum::APROVADO]);

        return true;
    }
}