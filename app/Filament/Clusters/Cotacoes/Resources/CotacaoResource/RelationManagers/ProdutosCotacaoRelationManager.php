<?php

namespace App\Filament\Clusters\Cotacoes\Resources\CotacaoResource\RelationManagers;

use App\Actions\Cotacao\AddItemCotacao;
use App\Enums\StatusCotacaoEnum;
use App\Models\Cotacao;
use App\Models\Parceiro\Fornecedor;
use App\Models\ProdutoCotacao;
use App\Models\PropostaCotacao;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                    ->preload()
                    ->searchable()
                    ->relationship('produto','descricao')
                    ->required(),
                Forms\Components\TextInput::make('quantidade')
                    ->default(1)
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
                Tables\Actions\Action::make('nova-proposta')
                        ->label('Nova Proposta')
                        ->icon('heroicon-o-plus-circle')
                        ->iconButton()
                        ->form([
                            Select::make('fornecedor')
                                ->options(Fornecedor::query()->pluck('nome', 'id'))
                                ->preload()
                                ->searchable()
                                ->required(),

                            TextInput::make('produto')
                                ->readOnly()
                                ->default(fn(ProdutoCotacao $record) => $record->produto->descricao),

                            TextInput::make('valor')
                                ->numeric()
                                ->prefix('R$'),
                        ])
                        ->action(
                            function (array $data, ProdutoCotacao $record) {
                                PropostaCotacao::create([
                                    'cotacao_id'    => $record->cotacao_id,
                                    'produto_id'    => $record->produto_id,
                                    'fornecedor_id' => $data['fornecedor'],
                                    'valor'         => $data['valor'],
                                    'status'        => StatusCotacaoEnum::PENDENTE,
                                ]);
                                $record->update(
                                    [
                                        'status' => StatusCotacaoEnum::EM_ANDAMENTO
                                    ]
                                );
                                $record->cotacao->update(
                                    [
                                        'status' => StatusCotacaoEnum::EM_ANDAMENTO
                                    ]
                                );
                            }

                        ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
