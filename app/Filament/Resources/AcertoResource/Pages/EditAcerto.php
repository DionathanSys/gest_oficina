<?php

namespace App\Filament\Resources\AcertoResource\Pages;

use App\Filament\Resources\AcertoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAcerto extends EditRecord
{
    protected static string $resource = AcertoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
