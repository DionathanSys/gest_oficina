<?php

namespace App\Filament\Resources\OrdemServicoResource\Pages;

use App\Enums\StatusDiversos;
use App\Enums\StatusOrdemSankhya;
use App\Filament\Resources\OrdemServicoResource;
use App\Models\OrdemServico;
use App\Services\OrdemServicoService;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusDiversos::PENDENTE)),
            'concluído' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusDiversos::CONCLUIDO)),
            'abrir_ordem' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_sankhya', StatusOrdemSankhya::PENDENTE))
                ->badge(OrdemServico::query()->where('status_sankhya', StatusOrdemSankhya::PENDENTE)->count())
                ->badgeColor('info'),
            'encerrar_ordem' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusDiversos::CONCLUIDO)
                                                            ->whereIn('status_sankhya', [StatusOrdemSankhya::PENDENTE, StatusOrdemSankhya::ABERTO]))
                                                            ->badge(OrdemServico::query()
                                                                ->where('status', StatusOrdemSankhya::CONCLUIDO)
                                                                ->where('status_sankhya', [StatusOrdemSankhya::PENDENTE, StatusOrdemSankhya::ABERTO])->count())
                                                            ->badgeColor('info'),

        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        if(Auth::user()->name == 'Angelica'){
            return 'abrir_ordem';
        }

        return 'pendente';

    }


    
}
