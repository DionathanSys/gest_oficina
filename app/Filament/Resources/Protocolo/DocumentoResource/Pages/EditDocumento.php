<?php

namespace App\Filament\Resources\Protocolo\DocumentoResource\Pages;

use App\Filament\Resources\Protocolo\DocumentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumento extends EditRecord
{
    protected static string $resource = DocumentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
