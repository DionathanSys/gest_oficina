<?php

namespace App\Filament\Resources\OrdemServicoResource\Pages;

use App\Filament\Resources\OrdemServicoResource;
use App\Models\OrdemServico;
use App\Services\OrdemServicoService;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrdemServicos extends ListRecords
{
    protected static string $resource = OrdemServicoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle')
                ->label('Novo')
                ->successRedirectUrl(fn(OrdemServico $record) => OrdemServicoResource::getUrl('edit', ['record' => $record->id])),
        ];
    }

    protected function handleRecordCreation(array $data): OrdemServico
    {
        return OrdemServicoService::create($data);
    }
}
