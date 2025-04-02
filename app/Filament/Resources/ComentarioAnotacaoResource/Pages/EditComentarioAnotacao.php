<?php

namespace App\Filament\Resources\ComentarioAnotacaoResource\Pages;

use App\Filament\Resources\ComentarioAnotacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComentarioAnotacao extends EditRecord
{
    protected static string $resource = ComentarioAnotacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
