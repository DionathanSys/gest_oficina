<?php

namespace App\Filament\Resources\ViagemAgroResource\Pages;

use App\Filament\Resources\ViagemAgroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditViagemAgro extends EditRecord
{
    protected static string $resource = ViagemAgroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
