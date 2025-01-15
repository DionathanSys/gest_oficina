<?php

namespace App\Filament\Resources\AnotacaoVeiculoResource\Pages;

use App\Enums\Prioridade;
use App\Enums\StatusDiversos;
use App\Filament\Resources\AnotacaoVeiculoResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAnotacaoVeiculos extends ListRecords
{
    protected static string $resource = AnotacaoVeiculoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'todos' => Tab::make(),
            'pendente' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', '<>', StatusDiversos::CONCLUIDO)),
            'concluído' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', '=', StatusDiversos::CONCLUIDO)),
            'alta' => Tab::make('Prior. Alta')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', '<>', StatusDiversos::CONCLUIDO)
                                                            ->where('prioridade', '=', Prioridade::ALTA)),
            'urgente' => Tab::make('Prior. Urg.')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', '<>', StatusDiversos::CONCLUIDO)
                                                            ->where('prioridade', '=', Prioridade::URGENTE)),
            
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'pendente';
    }
}
