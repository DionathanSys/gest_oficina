<?php

namespace App\Filament\Resources\Protocolo\DocumentoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotasFiscaisRelationManager extends RelationManager
{
    protected static string $relationship = 'notasFiscais';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nro_nota')
                    ->required()
                    ->maxLength(255),
                TextInput::make('valor')
                    ->numeric(2)
                    ->prefix('R$'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nro_nota')
            ->columns([
                Tables\Columns\TextColumn::make('nro_nota'),
                Tables\Columns\TextColumn::make('valor')
                    ->numeric(decimalPlaces: 2, locale: 'pt-BR')
                    ->prefix('R$'),
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
