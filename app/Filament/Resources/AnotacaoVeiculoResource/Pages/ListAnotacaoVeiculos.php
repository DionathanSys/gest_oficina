<?php

namespace App\Filament\Resources\AnotacaoVeiculoResource\Pages;

use App\Enums\Prioridade;
use App\Enums\StatusDiversos;
use App\Enums\TipoAnotacao;
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
            Actions\CreateAction::make()
                ->keyBindings(['ctrl+n']),
        ];
    }

    public function getTabs(): array
    {
        return [
            'todos' => Tab::make(),
            'pendente' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', '<>', StatusDiversos::CONCLUIDO)),
            'pneus' => Tab::make('Pneus')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', '<>', StatusDiversos::CONCLUIDO)
                                                            ->whereIn('tipo_anotacao', [TipoAnotacao::PNEU, TipoAnotacao::INSPECAO_PNEU])),

            'alta' => Tab::make('Prior. Alta')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', '<>', StatusDiversos::CONCLUIDO)
                                                            ->where('prioridade', '=', Prioridade::ALTA)),
            'urgente' => Tab::make('Prior. Urg.')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', '<>', StatusDiversos::CONCLUIDO)
                                                            ->where('prioridade', '=', Prioridade::URGENTE)),
            'concluído' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', '=', StatusDiversos::CONCLUIDO)),
            
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'pendente';
    }
}
