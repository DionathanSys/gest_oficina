<?php

namespace App\Filament\Clusters\Cotacoes\Resources\CotacaoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PropostasRelationManager extends RelationManager
{
    protected static string $relationship = 'propostas';

    public function form(Form $form): Form
    {
        return $form
            ->columns(4)
            ->schema([
               
                Forms\Components\Select::make('fornecedor_id')
                    ->columnSpan(2)
                    ->relationship('fornecedor', 'nome')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('valor')
                    ->columnSpan(1)
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->columnSpan(1)
                    ->default('Pendente')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('observacao')
                    ->columnSpanFull()
                    ->required()
                    ->maxLength(255),
                
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('cotacao')
            ->columns([
                Tables\Columns\TextColumn::make('cotacao'),
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
