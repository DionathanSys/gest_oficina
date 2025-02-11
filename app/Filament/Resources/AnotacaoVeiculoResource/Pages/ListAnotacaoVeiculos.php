<?php

namespace App\Filament\Resources\AnotacaoVeiculoResource\Pages;

use App\Enums\Prioridade;
use App\Enums\StatusDiversos;
use App\Enums\TipoAnotacao;
use App\Filament\Resources\AnotacaoVeiculoResource;
use App\Models\AnotacaoVeiculo;
use Filament\Actions;
use Filament\Forms\Components\{Select, DatePicker};
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
                ->keyBindings(['ctrl+n'])
                ->after(function(AnotacaoVeiculo $record){
                    session()->put('veiculo_id',       $record->veiculo_id);
                    session()->put('tipo_anotacao',    $record->tipo_anotacao);
                    session()->put('status.anotacao',  $record->status);
                    session()->put('prioridade',       $record->prioridade);
                    session()->put('data_referencia',  $record->data_referencia);
                    session()->put('km',               $record->km);
                }),
            Actions\ActionGroup::make([
                Actions\Action::make('conf-oleo-m')
                    ->label('Conf. Óleo motor')
                    ->form([
                        Select::make('veiculo_id')
                            ->relationship('veiculo', 'placa')
                            ->searchable()
                            ->required(),

                        DatePicker::make('data_referencia')
                            ->default(now())
                            ->closeOnDateSelection()
                            ->native(false)
                            ->required(),   
                    ])
                    ->action(function(array $data){
                        AnotacaoVeiculo::create([
                            'veiculo_id' => $data['veiculo_id'],
                            'data_referencia' => $data['data_referencia'],
                            'item_manutencao_id' => 56,
                            'tipo_anotacao' => TipoAnotacao::INSPECAO_PERIODICA,
                            'status' => StatusDiversos::CONCLUIDO,
                            'prioridade' => Prioridade::BAIXA,
                        ]);

                    }),
            ])
            ->button()
            ->label('Ações'),

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
