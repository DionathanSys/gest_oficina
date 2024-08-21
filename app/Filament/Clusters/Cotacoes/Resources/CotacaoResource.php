<?php

namespace App\Filament\Clusters\Cotacoes\Resources;

use App\Filament\Clusters\Cotacoes;
use App\Filament\Clusters\Cotacoes\Resources\CotacaoResource\Pages;
use App\Filament\Clusters\Cotacoes\Resources\CotacaoResource\RelationManagers;
use App\Filament\Clusters\Cotacoes\Resources\CotacaoResource\RelationManagers\ProdutoRelationManager;
use App\Filament\Clusters\Cotacoes\Resources\CotacaoResource\RelationManagers\PropostasRelationManager;
use App\Models\Cotacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CotacaoResource extends Resource
{
    protected static ?string $model = Cotacao::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Cotações';
    
    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Cotacoes::class;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(8)
            ->schema([
                Forms\Components\Select::make('produto_id')
                    ->columnSpan(6)
                    ->relationship('produto', 'descricao')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('quantidade')
                    ->columnSpan(1)
                    ->required()
                    ->numeric(),

                Forms\Components\Select::make('prioridade')
                    ->columnSpan(1)
                    ->options([
                        'baixa' => 'Baixa',
                        'media' => 'Média',
                        'alta' => 'Alta',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('setor')
                        ->columnSpan(2)
                    ->required()
                    ->maxLength(255),

                Forms\Components\DatePicker::make('data')
                        ->columnSpan(1)
                    ->required(),

                Forms\Components\TextInput::make('status')
                        ->columnSpan(1)
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('observacao')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('produto.descricao')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantidade')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('prioridade')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('setor')
                    ->searchable(),

                Tables\Columns\TextColumn::make('data')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->searchable(),

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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // ProdutoRelationManager::class,
            PropostasRelationManager::class
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
