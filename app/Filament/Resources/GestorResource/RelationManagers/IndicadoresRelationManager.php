<?php

namespace App\Filament\Resources\GestorResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IndicadoresRelationManager extends RelationManager
{
    protected static string $relationship = 'indicadores';

    public function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Forms\Components\TextInput::make('descricao')
                    ->label('Descrição')
                    ->columnSpan(4)
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('peso')
                    ->required()
                    ->columnSpan(2)
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('tipo')
                    ->required()
                    ->columnSpan(3)
                    ->options([
                        'INDIVIDUAL' => 'INDIVIDUAL',
                        'COLETIVO' => 'COLETIVO',
                    ])
                    ->default('INDIVIDUAL'),
                // Forms\Components\Select::make('gestor_id')
                //     ->label('Gestor')
                //     ->columnSpan(4)
                //     ->relationship('gestor', 'nome')
                //     ->preload()
                //     ->searchable()
                //     ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('descricao')
                    ->label('Descrição'),
                Tables\Columns\TextColumn::make('peso'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
