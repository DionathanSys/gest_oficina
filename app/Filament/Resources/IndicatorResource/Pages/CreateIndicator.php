<?php

namespace App\Filament\Resources\IndicatorResource\Pages;

use App\Actions\Indicators\VincularIndicadorColetivoAction;
use App\Filament\Resources\IndicatorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateIndicator extends CreateRecord
{
    protected static string $resource = IndicatorResource::class;

     protected function afterCreate(): void
    {
        ds($this->record);
        if($this->record->tipo === 'COLETIVO') {
            $data['descricao'] = 'Resultado Coletivo';
            VincularIndicadorColetivoAction::execute($this->record);
        }


    }
}
