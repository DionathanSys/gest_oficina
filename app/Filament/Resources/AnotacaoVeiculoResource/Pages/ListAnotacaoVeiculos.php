<?php

namespace App\Filament\Resources\AnotacaoVeiculoResource\Pages;

use App\Filament\Resources\AnotacaoVeiculoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnotacaoVeiculos extends ListRecords
{
    protected static string $resource = AnotacaoVeiculoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
