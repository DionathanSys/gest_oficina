<?php

namespace App\Filament\Resources\OrdemServicoResource\Pages;

use App\Enums\StatusOrdemSankhya;
use App\Filament\Resources\OrdemServicoResource;
use App\Models\OrdemServico;
use App\Services\OrdemServicoService;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ListOrdemServicos extends ListRecords
{
    protected static string $resource = OrdemServicoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle')
                ->label('Novo')
                ->action(fn(array $data) => OrdemServicoService::create($data))
                ->successRedirectUrl(fn(OrdemServico $record) => OrdemServicoResource::getUrl('edit', ['record' => $record->id])),
        ];
    }

    public function getTabs(): array
    {
        return [
            'todos' => Tab::make(),
            'pendente' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusOrdemSankhya::PENDENTE)),
            'concluído' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusOrdemSankhya::CONCLUIDO)),

        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'pendente';
    }


    
}
