<?php

namespace App\Filament\Exports;

use App\Models\Acerto;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AcertoExporter extends Exporter
{
    protected static ?string $model = Acerto::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('fechamento')
                ->enabledByDefault(false),
            ExportColumn::make('nro_acerto'),
            ExportColumn::make('motorista'),
            ExportColumn::make('vlr_fechamento')
                ->formatStateUsing(fn (string $state): string => number_format((float) $state, 2, ',', '.')),
            ExportColumn::make('vlr_media')
                ->formatStateUsing(fn (string $state): string => number_format((float) $state, 2, ',', '.')),
            ExportColumn::make('vlr_inss')
                ->formatStateUsing(fn (string $state): string => number_format((float) $state, 2, ',', '.'))
                ->enabledByDefault(false),
            ExportColumn::make('vlr_irrf')
                ->formatStateUsing(fn (string $state): string => number_format((float) $state, 2, ',', '.'))
                ->enabledByDefault(false),
            ExportColumn::make('vlr_manutencao')
                ->formatStateUsing(fn (string $state): string => number_format((float) $state, 2, ',', '.')),
            ExportColumn::make('vlr_diferenca')
                ->formatStateUsing(fn (string $state): string => number_format((float) $state, 2, ',', '.')),
            ExportColumn::make('vlr_comissao')
                ->formatStateUsing(fn (string $state): string => number_format((float) $state, 2, ',', '.')),
            ExportColumn::make('created_at')
                ->enabledByDefault(false),
            ExportColumn::make('updated_at')
                ->enabledByDefault(false),
            ExportColumn::make('fechado')
                ->enabledByDefault(false),
            ExportColumn::make('complementos')
                ->state(function (Acerto $record): string {
                    return $record->getComplemento();
                }),
            ExportColumn::make('salario_liquido')
                ->state(function (Acerto $record): string {
                    return number_format((float) $record->getSalarioLiquido(), 2, ',', '.');
                })
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your acerto export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
