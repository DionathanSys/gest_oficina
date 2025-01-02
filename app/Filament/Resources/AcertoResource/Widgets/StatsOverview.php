<?php

namespace App\Filament\Resources\AcertoResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Faturamento', 'R$ 1.066.529,00'),
            Stat::make('Total de Acertos', 'R$ 183.569,00'),
            Stat::make('Pr. Segurança', 'R$ 10.839,00'),
            Stat::make('% Folha', '17,36 %'),
        ];
    }
}
