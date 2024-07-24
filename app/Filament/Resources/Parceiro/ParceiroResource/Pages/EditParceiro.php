<?php

namespace App\Filament\Resources\Parceiro\ParceiroResource\Pages;

use App\Filament\Resources\Parceiro\ParceiroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditParceiro extends EditRecord
{
    protected static string $resource = ParceiroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
