<?php

namespace App\Filament\Resources\AnotacaoVeiculoResource\Pages;

use App\Filament\Resources\AnotacaoVeiculoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnotacaoVeiculo extends EditRecord
{
    protected static string $resource = AnotacaoVeiculoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
