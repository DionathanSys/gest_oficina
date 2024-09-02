<?php

namespace App\Filament\Resources\AcertoResource\Pages;

use App\Filament\Resources\AcertoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use JoseEspinal\RecordNavigation\Traits\HasRecordsList;

class ListAcertos extends ListRecords
{
    use HasRecordsList;
        
    protected static string $resource = AcertoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
