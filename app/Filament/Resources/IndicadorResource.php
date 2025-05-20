<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IndicadorResource\Pages;
use App\Filament\Resources\IndicadorResource\RelationManagers;
use App\Filament\Resources\IndicadorResource\RelationManagers\GestoresRelationManager;
use App\Models\Indicador;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IndicadorResource extends Resource
{
    protected static ?string $model = Indicador::class;

    protected static ?string $navigationGroup = 'Indicadores';

    protected static ?string $navigationLabel = 'Indicadores';

    protected static ?string $label = 'Indicador';

    protected static ?string $pluralModelLabel = 'Indicadores';

    protected static ?string $pluralLabel = 'pluralLabel';


    public static function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Forms\Components\TextInput::make('descricao')
                    ->label('Descrição')
                    ->columnSpan(4)
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('peso')
                    ->required()
                    ->columnSpan(2)
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('tipo')
                    ->required()
                    ->columnSpan(2)
                    ->options([
                        'INDIVIDUAL' => 'INDIVIDUAL',
                        'COLETIVO' => 'COLETIVO',
                    ])
                    ->default('INDIVIDUAL'),
                Forms\Components\CheckboxList::make('gestores')
                    ->label('Gestor')
                    ->columns(4)
                    ->columnSpanFull()
                    ->columnStart(1)
                    ->bulkToggleable()
                    ->relationship('gestores', 'nome'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('descricao')
                    ->label('Descrição')
                    ->searchable(),
                Tables\Columns\TextColumn::make('peso')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo'),
                Tables\Columns\TextColumn::make('gestores.nome')
                    ->limitList(1)
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            // ->groups([
            //     Tables\Grouping\Group::make('gestor.nome')
            //         ->label('Gestor'),
            // ])
            // ->defaultGroup('gestor.nome')
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
            GestoresRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIndicadors::route('/'),
            // 'create' => Pages\CreateIndicador::route('/create'),
            'edit' => Pages\EditIndicador::route('/{record}/edit'),
        ];
    }
}
