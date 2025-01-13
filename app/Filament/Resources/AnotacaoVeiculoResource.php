<?php

namespace App\Filament\Resources;

use App\Enums\Prioridade;
use App\Enums\StatusDiversos;
use App\Filament\Resources\AnotacaoVeiculoResource\Pages;
use App\Filament\Resources\AnotacaoVeiculoResource\RelationManagers;
use App\Models\AnotacaoVeiculo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnotacaoVeiculoResource extends Resource
{
    protected static ?string $model = AnotacaoVeiculo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Forms\Components\Select::make('veiculo_id')
                    ->columnSpan(2)
                    ->relationship('veiculo', 'placa')
                    ->required(),
                Forms\Components\DatePicker::make('data_referencia')
                    ->columnSpan(2)
                    ->default(now())
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('tipo_anotacao')
                    ->columnSpan(2)
                    ->label('Tipo Anotação')
                    ->options(function () {
                        return collect(Prioridade::cases())
                            ->mapWithKeys(fn($prioridade) => [$prioridade->value => $prioridade->value])
                            ->toArray();
                    })
                    ->required(),
                Forms\Components\Select::make('status')
                    ->columnSpan(2)
                    ->options(function () {
                        return collect(StatusDiversos::cases())
                            ->mapWithKeys(fn($prioridade) => [$prioridade->value => $prioridade->value])
                            ->toArray();
                    })
                    ->required(),
                Forms\Components\Select::make('prioridade')
                    ->columnSpan(2)
                    ->required()
                    ->options(function () {
                        return collect(Prioridade::cases())
                            ->mapWithKeys(fn($prioridade) => [$prioridade->value => $prioridade->value])
                            ->toArray();
                    }),
                Forms\Components\Select::make('item_manutencao_id')
                    ->columnSpan(4)
                    ->relationship('itemManutencao', 'descricao')
                    ->default(null),
                Forms\Components\TextInput::make('observacao')
                    ->columnSpan(8)
                    ->label('Observação')
                    ->maxLength(255)
                    ->default(null),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('veiculo.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('itemManutencao.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('observacao')
                    ->searchable(),
                Tables\Columns\TextColumn::make('data_referencia')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo_anotacao')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('prioridade')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnotacaoVeiculos::route('/'),
            'create' => Pages\CreateAnotacaoVeiculo::route('/create'),
            'edit' => Pages\EditAnotacaoVeiculo::route('/{record}/edit'),
        ];
    }
}
