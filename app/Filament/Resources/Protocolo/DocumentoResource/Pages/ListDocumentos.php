<?php

namespace App\Filament\Resources\Protocolo\DocumentoResource\Pages;

use App\Filament\Resources\Protocolo\DocumentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentos extends ListRecords
{
    protected static string $resource = DocumentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
