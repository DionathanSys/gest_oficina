<?php

namespace App\Filament\Clusters\Cotacoes\Resources\ProdutoCotacaoResource\Pages;

use App\Filament\Clusters\Cotacoes\Resources\ProdutoCotacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProdutoCotacaos extends ManageRecords
{
    protected static string $resource = ProdutoCotacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Novo Item'),
        ];
    }
}
