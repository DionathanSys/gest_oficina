<?php

namespace App\Filament\Resources\ItemManutencaoResource\Pages;

use App\Filament\Resources\ItemManutencaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemManutencao extends EditRecord
{
    protected static string $resource = ItemManutencaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
