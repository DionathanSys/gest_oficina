<?php

namespace App\Filament\Resources\AnotacaoVeiculoResource\Pages;

use App\Enums\Prioridade;
use App\Enums\StatusDiversos;
use App\Enums\TipoAnotacao;
use App\Filament\Resources\AnotacaoVeiculoResource;
use App\Models\AnotacaoVeiculo;
use Filament\Actions;
use Filament\Forms\Components\{Select, DatePicker, Toggle};
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
                            ->preload()
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
                Actions\Action::make('agendar-lavacao')
                    ->label('Agendar Lavagem')
                    ->form([
                        Select::make('veiculo_id')
                            ->relationship('veiculo', 'placa')
                            ->searchable()
                            ->preload()
                            ->required(),

                        DatePicker::make('data_referencia')
                            ->default(now())
                            ->closeOnDateSelection()
                            ->native(false)
                            ->required(),   
                        Select::make('observacao')
                            ->label('Posto')
                            ->options([
                                'MF'        => 'MF',
                                'Pacheco'   => 'Pacheco',
                            ])
                            ->default('MF'),
                        Toggle::make('concluido')
                            ->default(false),

                    ])
                    ->action(function(array $data){
                        AnotacaoVeiculo::create([
                            'veiculo_id' => $data['veiculo_id'],
                            'data_referencia' => $data['data_referencia'],
                            'observacao' => 'Posto '.$data['observacao'],
                            'item_manutencao_id' => 85,
                            'tipo_anotacao' => TipoAnotacao::OBSERVACAO,
                            'status' => $data['concluido'] ? StatusDiversos::CONCLUIDO : StatusDiversos::PENDENTE,
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
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', [StatusDiversos::EXECUCAO, StatusDiversos::PENDENTE])),
            'pneus' => Tab::make('Pneus')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', [StatusDiversos::PENDENTE, StatusDiversos::EXECUCAO, StatusDiversos::VALIDAR])
                                                            ->whereIn('tipo_anotacao', [TipoAnotacao::PNEU, TipoAnotacao::INSPECAO_PNEU])),
            'alta' => Tab::make('Prior. Alta/Urg.')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', '<>', StatusDiversos::CONCLUIDO)
                                                            ->whereIn('prioridade', [Prioridade::ALTA, Prioridade::URGENTE])),
            'validar' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusDiversos::VALIDAR)),
            'concluído' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusDiversos::CONCLUIDO)),

            'cancelado' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusDiversos::CANCELADO)),
            
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'pendente';
    }
}
