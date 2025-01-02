<?php

namespace App\Actions;

use App\Enums\StatusCotacaoEnum;
use App\Models\ProdutoCotacao;

class AprovarProdutoAction
{
    public static function exec(ProdutoCotacao $produtoCotacao)
    {
        $propostas = $produtoCotacao->propostas;

        $propostaAprovada = $propostas->sortBy('valor')->first();

        $propostas->each(function ($proposta) use ($propostaAprovada) {
            $proposta->status = $proposta->is($propostaAprovada) ? StatusCotacaoEnum::APROVADO : StatusCotacaoEnum::REPROVADO;
            $proposta->save();
        });
    }
}