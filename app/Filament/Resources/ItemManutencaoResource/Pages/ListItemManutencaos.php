<?php

namespace App\Filament\Resources\ItemManutencaoResource\Pages;

use App\Filament\Resources\ItemManutencaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemManutencaos extends ListRecords
{
    protected static string $resource = ItemManutencaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
