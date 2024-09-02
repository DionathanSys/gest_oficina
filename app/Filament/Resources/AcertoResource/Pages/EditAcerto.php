<?php

namespace App\Filament\Resources\AcertoResource\Pages;

use App\Filament\Resources\AcertoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use JoseEspinal\RecordNavigation\Traits\HasRecordNavigation;

class EditAcerto extends EditRecord
{
    use HasRecordNavigation;
    
    protected static string $resource = AcertoResource::class;

    protected function getHeaderActions(): array
    {
        return array_merge(parent::getHeaderActions(), $this->getNavigationActions());

        // return [
        //     Actions\DeleteAction::make(),
        // ];
    }
}
