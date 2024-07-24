<?php

namespace App\Filament\Clusters\Clientes\Resources\FaturaResource\Pages;

use App\Filament\Clusters\Clientes\Resources\FaturaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFaturas extends ListRecords
{
    protected static string $resource = FaturaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
