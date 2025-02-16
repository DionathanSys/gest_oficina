<?php

namespace App\Filament\Clusters\Cotacoes\Resources\CotacaoResource\Pages;

use App\Filament\Clusters\Cotacoes\Resources\CotacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
// use Illuminate\Database\Eloquent\Builder;
// use Filament\Resources\Components\Tab;

class ListCotacaos extends ListRecords
{
    protected static string $resource = CotacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nova Cotação'),
        ];
    }

    // public function getTabs(): array
    // {
    //     return [
    //         'todos' => Tab::make(),
    //         'pendente' => Tab::make()
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Pendente')),
            
    //     ];
    // }
}
