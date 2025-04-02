<?php

namespace App\Filament\Resources\AcertoResource\Pages;

use App\Filament\Exports\AcertoExporter;
use App\Filament\Resources\AcertoResource;
use App\Filament\Resources\AcertoResource\Widgets\Acertos;
use App\Filament\Resources\AcertoResource\Widgets\StatsOverview;
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
            Actions\ExportAction::make()
                ->exporter(AcertoExporter::class),
        ];
    }

}
