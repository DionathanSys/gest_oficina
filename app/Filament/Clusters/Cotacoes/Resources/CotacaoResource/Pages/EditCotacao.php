<?php

namespace App\Filament\Clusters\Cotacoes\Resources\CotacaoResource\Pages;

use App\Filament\Clusters\Cotacoes\Resources\CotacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCotacao extends EditRecord
{
    protected static string $resource = CotacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
