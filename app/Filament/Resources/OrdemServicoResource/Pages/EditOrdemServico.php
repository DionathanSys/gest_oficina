<?php

namespace App\Filament\Resources\OrdemServicoResource\Pages;

use App\Actions\OrdemServico\EncerrarOrdem;
use App\Filament\Resources\OrdemServicoResource;
use Filament\Actions;
use Filament\Actions\Modal\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Resources\Pages\EditRecord;

class EditOrdemServico extends EditRecord
{
    protected static string $resource = OrdemServicoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('encerrar')
                ->label('Encerrar')
                ->form([
                    Forms\Components\DatePicker::make('data_encerramento')
                        ->label('Data de Encerramento')
                        ->required()
                        ->default(now())
                        ->displayFormat('d/m/Y'),
                ])
                ->action(fn(array $data) => EncerrarOrdem::execute(
                    $this->record,
                    $data['data_encerramento'] ?? now(),
                ))
                ->after(fn() => redirect()->route(OrdemServicoResource::getUrl('edit', ['record' => $this->record->id]))),
        ];
    }
}
