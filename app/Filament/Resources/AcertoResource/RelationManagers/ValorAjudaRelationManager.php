<?php

namespace App\Filament\Resources\AcertoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ValorAjudaRelationManager extends RelationManager
{
    protected static string $relationship = 'valor_ajuda';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('vlr_ajuda')
                ->numeric()
                ->prefix('R$')
                ->required(),

                Forms\Components\Select::make('motivo')
                    ->required()
                    ->default('Ref. Aj. Custo')
                    ->options([
                        'Ref. Aj. Custo' => 'Ajuda de Custo',
                        'Ref. Domingo(s)' => 'Domingo',
                        'Ref. Dias de Base' => 'Dias Base',
                        'Ref. Manobra' => 'Manobra',
                        'Ref. Viagens em outro Caminhão' => 'Viagens',
                        'Ref. Ferista' => 'Ferista',
                        'Ref. Quebra de caminhão' => 'Quebra de caminhão',
                        'Ref. Treinamento de motorista' => 'Treinamento de motorista',
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('acerto_id')
            ->columns([
                Tables\Columns\TextColumn::make('acerto_id'),
                Tables\Columns\TextColumn::make('motivo'),
                Tables\Columns\TextColumn::make('vlr_ajuda')
                    ->money('BRL'),
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
