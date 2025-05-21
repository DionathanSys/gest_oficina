<?php

namespace App\Filament\Resources\ManagerResource\RelationManagers;

use App\Filament\Resources\IndicatorResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IndicatorsRelationManager extends RelationManager
{
    protected static string $relationship = 'indicators';

    protected static ?string $title = 'Indicadores';

    protected static bool $isLazy = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('descricao')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('peso')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('tipo')
                    ->options([
                        'COLETIVO' => 'Coletivo',
                        'INDIVIDUAL' => 'Individual',
                    ])
                    ->default('INDIVIDUAL')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('descricao')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('descricao')
                    ->label('Descrição'),
                Tables\Columns\TextColumn::make('peso'),
                Tables\Columns\TextColumn::make('tipo'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Indicador')
                    ->icon('heroicon-o-plus')
                    ->modalHeading('Novo Indicador')
                    ->form(fn(Forms\Form $form) => IndicatorResource::form($form)),

                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                        ->modalHeading('Vincular Indicador')
                        ->recordSelect(
                            fn (Forms\Components\Select $select) => $select->placeholder('Selecionar Indicador'),
                        ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton(),
                Tables\Actions\DetachAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Sem registros')
            ->emptyStateDescription('Clique em "+ Indicador" ou "Vincular" para adicionar um indicador.');
    }
}
