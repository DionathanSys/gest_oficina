<?php

namespace App\Filament\Resources\ResultadoIndicadorResource\Pages;

use App\Filament\Resources\ResultadoIndicadorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResultadoIndicadors extends ListRecords
{
    protected static string $resource = ResultadoIndicadorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
