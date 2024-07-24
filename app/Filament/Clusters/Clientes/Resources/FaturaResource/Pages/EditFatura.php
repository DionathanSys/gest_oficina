<?php

namespace App\Filament\Clusters\Clientes\Resources\FaturaResource\Pages;

use App\Filament\Clusters\Clientes\Resources\FaturaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFatura extends EditRecord
{
    protected static string $resource = FaturaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
