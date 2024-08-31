<?php

namespace App\Filament\Resources\AcertoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ViagensDuplaRelationManager extends RelationManager
{
    protected static string $relationship = 'viagens_dupla';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('motorista_dupla_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('motorista_dupla_id')
            ->columns([
                Tables\Columns\TextColumn::make('motorista_dupla_id'),
                Tables\Columns\TextColumn::make('nro_nota'),
                Tables\Columns\TextColumn::make('dupla'),
                Tables\Columns\TextColumn::make('frete'),
                Tables\Columns\TextColumn::make('comissao'),
                Tables\Columns\TextColumn::make('vlr_comissao'),
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
