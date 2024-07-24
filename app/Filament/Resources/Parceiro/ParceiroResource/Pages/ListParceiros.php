<?php

namespace App\Filament\Resources\Parceiro\ParceiroResource\Pages;

use App\Filament\Resources\Parceiro\ParceiroResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListParceiros extends ListRecords
{
    protected static string $resource = ParceiroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
