<?php

namespace App\Filament\Clusters\Cotacoes\Resources;

use App\Filament\Clusters\Cotacoes;
use App\Filament\Clusters\Cotacoes\Resources\PropostaCotacaoResource\Pages;
use App\Filament\Clusters\Cotacoes\Resources\PropostaCotacaoResource\RelationManagers;
use App\Models\PropostaCotacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PropostaCotacaoResource extends Resource
{
    protected static ?string $model = PropostaCotacao::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationLabel = 'Propostas';

    protected static ?string $label = 'Propostas Cotações';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $cluster = Cotacoes::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('cotacao_id')
                    ->relationship('cotacao', 'id')
                    ->required(),
                Forms\Components\TextInput::make('produto_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('fornecedor_id')
                    ->relationship('fornecedor', 'id')
                    ->required(),
                Forms\Components\TextInput::make('valor')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('observacao')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cotacao.id')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('produto.descricao')
                    ->numeric()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fornecedor.nome')
                    ->numeric()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('valor')
                    ->money('BRL')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->searchable(),

                Tables\Columns\TextColumn::make('observacao')
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
            ->groups([
                'cotacao.id',
                'fornecedor.nome',
            ])
            ->striped()
            ->defaultSort('produto.descricao')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPropostaCotacaos::route('/'),
            'create' => Pages\CreatePropostaCotacao::route('/create'),
            // 'edit' => Pages\EditPropostaCotacao::route('/{record}/edit'),
        ];
    }
}
