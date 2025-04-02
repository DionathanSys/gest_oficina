<?php

namespace App\Filament\Resources\AcertoResource\Widgets;

use App\Models\ViagemAgro;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Faturamento', fn()=> 'R$ '.number_format(ViagemAgro::where('fechamento', '202501')->sum('frete')/100, 2, ',', '.')),
            // Stat::make('Total de Acertos', 'R$ 183.569,00'),
            // Stat::make('Pr. Segurança', 'R$ 10.839,00'),
            // Stat::make('% Folha', '17,36 %'),
        ];
    }
}
