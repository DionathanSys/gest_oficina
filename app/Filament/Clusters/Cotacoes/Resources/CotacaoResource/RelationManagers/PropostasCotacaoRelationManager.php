<?php

namespace App\Filament\Clusters\Cotacoes\Resources\CotacaoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PropostasCotacaoRelationManager extends RelationManager
{
    protected static string $relationship = 'propostas_cotacao';

    protected static ?string $title = 'Propostas';


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
                Tables\Columns\TextColumn::make('produto.descricao')
                ->label('Descrição'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('fornecedor.nome'),
                Tables\Columns\TextColumn::make('valor')
                    ->money('BRL'),
            ])
            ->filters([
                
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
