<?php

namespace App\Services;

use App\Enums\StatusDiversos;
use App\Models\OrdemServico;

class OrdemServicoService
{
    public function create(array $data): OrdemServico
    {

        return OrdemServico::create($data);
    }

    public function update(OrdemServico $ordemServico, array $data): OrdemServico
    {
        $ordemServico->update($data);

        return $ordemServico;
    }

    public function delete(OrdemServico $ordemServico): void
    {
        $ordemServico->delete();
    }

    public static function addItem(OrdemServico $ordemServico, array $data): void
    {
        $data['status'] = StatusDiversos::PENDENTE;
        $ordemServico->itens()->create($data);
    }
    
}