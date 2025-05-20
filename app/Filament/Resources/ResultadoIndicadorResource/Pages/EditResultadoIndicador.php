<?php

namespace App\Filament\Resources\ResultadoIndicadorResource\Pages;

use App\Filament\Resources\ResultadoIndicadorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResultadoIndicador extends EditRecord
{
    protected static string $resource = ResultadoIndicadorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
