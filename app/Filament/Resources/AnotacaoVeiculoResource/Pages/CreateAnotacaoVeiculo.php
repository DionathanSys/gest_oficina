<?php

namespace App\Filament\Resources\AnotacaoVeiculoResource\Pages;

use App\Filament\Resources\AnotacaoVeiculoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAnotacaoVeiculo extends CreateRecord
{
    protected static string $resource = AnotacaoVeiculoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['veiculo_id'] = session()->put('veiculo_id', $data['veiculo_id']);
        $data['tipo_anotacao'] = session()->put('tipo_anotacao', $data['tipo_anotacao']);
        $data['status'] = session()->put('status.anotacao', $data['status']);
        $data['prioridade'] = session()->put('prioridade', $data['prioridade']);
        $data['data_referencia'] = session()->put('data_referencia', $data['data_referencia']);
        $data['km'] = session()->put('km', $data['km']);

        return $data;
    }
}
