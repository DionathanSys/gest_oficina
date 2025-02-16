<?php

namespace App\Filament\Clusters\Cotacoes\Resources;

use App\Actions\Cotacao\AddItemCotacao;
use App\Enums\StatusCotacaoEnum;
use App\Filament\Clusters\Cotacoes;
use App\Filament\Clusters\Cotacoes\Resources\CotacaoResource\Pages;
use App\Filament\Clusters\Cotacoes\Resources\CotacaoResource\RelationManagers;
use App\Filament\Clusters\Cotacoes\Resources\CotacaoResource\RelationManagers\ProdutosCotacaoRelationManager;
use App\Filament\Clusters\Cotacoes\Resources\CotacaoResource\RelationManagers\PropostasCotacaoRelationManager;
use App\Models\Cotacao;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CotacaoResource extends Resource
{
    protected static ?string $model = Cotacao::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Cotações';

    protected static ?int $navigationSort = null;

    protected static ?string $label = 'Cotações';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $cluster = Cotacoes::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('descricao')
                    ->maxLength(255)
                    ->default(null),

                Forms\Components\TextInput::make('setor')
                    ->maxLength(255)
                    ->default('Frota Agro'),

                Forms\Components\Select::make('prioridade')
                    ->default('Media')
                    ->options([
                        'Baixa' => 'Baixa',
                        'Media' => 'Média',
                        'Alta' => 'Alta',
                    ])
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'Pendente' => 'Pendente',
                        'Finalizado' => 'Finalizado',
                        'Cancelado' => 'Cancelado'
                    ])
                    ->default('Pendente')
                    ->required(),

                Forms\Components\DatePicker::make('data')
                    ->default(now())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('descricao')
                    ->label('Descrição')
                    ->searchable(),
                Tables\Columns\TextColumn::make('setor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('prioridade')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('data')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(function () {
                        return collect(StatusCotacaoEnum::cases())
                            ->mapWithKeys(fn($status) => [$status->value => $status->value])
                            ->toArray();
                    })
                    ->multiple()
                    ->default([StatusCotacaoEnum::PENDENTE->value, StatusCotacaoEnum::EM_ANDAMENTO->value])
            ])
            ->persistFiltersInSession()
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                    Action::make('Fechar')
                        ->icon('heroicon-o-lock-closed')
                        ->action(fn(Cotacao $record) => $record->update(['status' => 'Fechado'])),
                    Action::make('Reabrir')
                        ->icon('heroicon-o-lock-open')
                        ->action(fn(Cotacao $record) => $record->update(['status' => 'Pendente'])),
                    Action::make('Item')
                        ->icon('heroicon-o-plus')
                        ->action(fn(Cotacao $record, $data) => (new AddItemCotacao($record, $data))->handle())
                        ->form([
                            Select::make('produto_id')
                                ->label('Item')
                                ->required()
                                ->preload()
                                ->searchable()
                                ->relationship('produto', 'descricao'),
                            TextInput::make('quantidade'),
                            Textarea::make('observacao'),
                        ])

                ])->label('Ações')

            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProdutosCotacaoRelationManager::class,
            PropostasCotacaoRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCotacaos::route('/'),
            'create' => Pages\CreateCotacao::route('/create'),
            'edit' => Pages\EditCotacao::route('/{record}/edit'),
        ];
    }
}
