<?php

namespace App\Filament\Resources\IndicatorResultResource\Pages;

use App\Filament\Resources\IndicatorResultResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIndicatorResult extends EditRecord
{
    protected static string $resource = IndicatorResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
