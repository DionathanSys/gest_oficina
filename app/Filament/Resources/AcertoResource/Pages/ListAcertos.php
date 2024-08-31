<?php

namespace App\Filament\Resources\AcertoResource\Pages;

use App\Filament\Resources\AcertoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAcertos extends ListRecords
{
    protected static string $resource = AcertoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
