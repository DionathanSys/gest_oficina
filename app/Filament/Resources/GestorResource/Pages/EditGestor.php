<?php

namespace App\Filament\Resources\GestorResource\Pages;

use App\Filament\Resources\GestorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGestor extends EditRecord
{
    protected static string $resource = GestorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
