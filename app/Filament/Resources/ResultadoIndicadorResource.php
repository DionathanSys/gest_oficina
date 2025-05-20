<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResultadoIndicadorResource\Pages;
use App\Filament\Resources\ResultadoIndicadorResource\RelationManagers;
use App\Models\ResultadoIndicador;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResultadoIndicadorResource extends Resource
{
    protected static ?string $model = ResultadoIndicador::class;

    protected static ?string $navigationGroup = 'Resultados';

    protected static ?string $navigationLabel = 'Resultados';

    protected static ?string $pluralModelLabel = 'Resultados';

    protected static ?string $label = 'Resultado';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Forms\Components\Select::make('gestor_id')
                    ->label('Gestor')
                    ->columnSpan(3)
                    ->relationship('gestor', 'nome')
                    ->preload()
                    ->searchable(),
                Forms\Components\Select::make('indicador_id')
                    ->label('Indicador')
                    ->columnSpan(3)
                    ->relationship('indicador', 'descricao')
                    ->preload()
                    ->searchable(),
                Forms\Components\DatePicker::make('periodo')
                    ->label('Período')
                    ->columnSpan(2)
                    ->required(),
                Forms\Components\Select::make('resultado')
                    ->columnSpan(2)
                    ->options([
                        'ATENDIDO' => 'ATENDIDO',
                        'NÃO ATENDIDO' => 'NÃO ATENDIDO',
                    ]),
                Forms\Components\TextInput::make('pontuacao_obtida')
                    ->label('Pontuação Obtida')
                    ->columnSpan(2)
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gestor.nome')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('indicador.descricao')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('periodo')
                    ->label('Período')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('resultado'),
                Tables\Columns\TextColumn::make('pontuacao_obtida')
                    ->label('Pontuação Obtida')
                    ->numeric()
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
            'index' => Pages\ListResultadoIndicadors::route('/'),
            'create' => Pages\CreateResultadoIndicador::route('/create'),
            'edit' => Pages\EditResultadoIndicador::route('/{record}/edit'),
        ];
    }
}
