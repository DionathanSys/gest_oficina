<?php

namespace App\Filament\Resources\ManagerResource\RelationManagers;

use App\Filament\Resources\IndicatorResultResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IndicatorResultsRelationManager extends RelationManager
{
    protected static string $relationship = 'indicatorResults';

    protected static ?string $title = 'Resultados';

    protected static bool $isLazy = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Resultado')
                    ->icon('heroicon-o-plus')
                    ->modalHeading('Novo Resultado')
                    ->form(fn(Forms\Form $form) => IndicatorResultResource::form($form)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Sem registros')
            ->emptyStateDescription('Clique em "+ Resultado" para adicionar um novo registro.');
    }
}
