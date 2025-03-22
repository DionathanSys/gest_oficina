<?php

namespace App\Filament\Resources\ItemOrdemServicoResource\Pages;

use App\Filament\Resources\ItemOrdemServicoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageItemOrdemServicos extends ManageRecords
{
    protected static string $resource = ItemOrdemServicoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
