<?php

namespace App\Actions\Cotacao;

use App\Models\ProdutoCotacao;

class AddItemCotacao
{
    public static function execute(array $data)
    {
        $produtoCotacao = ProdutoCotacao::create($data);
    }
}