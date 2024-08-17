<?php

namespace App\Filament\Resources\PropostaCotacaoResource\Pages;

use App\Filament\Resources\PropostaCotacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePropostaCotacaos extends ManageRecords
{
    protected static string $resource = PropostaCotacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
