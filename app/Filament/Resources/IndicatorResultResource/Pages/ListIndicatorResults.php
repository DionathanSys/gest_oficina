<?php

namespace App\Filament\Resources\IndicatorResultResource\Pages;

use App\Filament\Resources\IndicatorResultResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIndicatorResults extends ListRecords
{
    protected static string $resource = IndicatorResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Resultado')
                ->icon('heroicon-o-plus'),
        ];
    }
}
