<?php

namespace App\Filament\Resources\AcertoResource\Widgets;

use App\Models\Acerto;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class Acertos extends BaseWidget
{

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn()=>Acerto::query()
                    ->where('fechamento', '202412')
                    ->get()
                    
            )
            ->columns([
                // ...
            ]);
    }
}
