<?php

namespace App\Filament\Clusters\Cotacoes\Resources\CotacaoResource\RelationManagers;

use App\Actions\Cotacao\AddItemCotacao;
use App\Enums\StatusCotacaoEnum;
use App\Models\Cotacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProdutosCotacaoRelationManager extends RelationManager
{
    protected static string $relationship = 'produtos_cotacao';

    protected static ?string $title = 'Produtos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('produto_id')
                    ->searchable()
                    ->relationship('produto','descricao')
                    ->required(),
                Forms\Components\TextInput::make('quantidade')
                    ->numeric()
                    ->required()
                    ->minValue(1),
                Forms\Components\Textarea::make('observacao')
                    ->label('Observações')
                    ->columnSpanFull()
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
                Tables\Columns\TextColumn::make('quantidade'),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus')
                    ->label('Produto')
                    ->action(fn(array $data) => (new AddItemCotacao($this->ownerRecord, $data))->handle()),
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
