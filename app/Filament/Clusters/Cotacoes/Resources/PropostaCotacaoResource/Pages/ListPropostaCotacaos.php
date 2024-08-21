<?php

namespace App\Filament\Clusters\Cotacoes\Resources\PropostaCotacaoResource\Pages;

use App\Filament\Clusters\Cotacoes\Resources\PropostaCotacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropostaCotacaos extends ListRecords
{
    protected static string $resource = PropostaCotacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
