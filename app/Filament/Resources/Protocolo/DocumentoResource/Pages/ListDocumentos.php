<?php

namespace App\Filament\Resources\Protocolo\DocumentoResource\Pages;

use App\Filament\Resources\Protocolo\DocumentoResource;
use App\Traits\HasTableEnvioDocumentoTabs;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListDocumentos extends ListRecords
{

    protected static string $resource = DocumentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Todos' => Tab::make(),
            'pendentes' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('envio', null)),
            'concluídos' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('envio', '!=', null)),
        ];
    }


}
