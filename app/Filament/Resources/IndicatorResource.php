<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IndicatorResource\Pages;
use App\Filament\Resources\IndicatorResource\RelationManagers;
use App\Filament\Resources\IndicatorResource\RelationManagers\ManagersRelationManager;
use App\Models\Indicator;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IndicatorResource extends Resource
{
    protected static ?string $model = Indicator::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $pluralModelLabel = 'Indicadores';

    protected static ?string $pluralLabel = 'Indicadores';

    protected static ?string $label = 'Indicador';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Forms\Components\TextInput::make('descricao')
                    ->columnSpan(5)
                    ->label('Descrição')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('peso')
                    ->columnSpan(2)
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('periodicidade')
                    ->columnSpan(2)
                    ->required()
                    ->options([
                        'MENSAL'        => 'Mensal',
                        'TRIMESTRAL'    => 'Trimestral',
                        'SEMESTRAL'     => 'Semestral',
                        'ANUAL'         => 'Anual',
                    ]),
                Forms\Components\Select::make('tipo')
                    ->columnSpan(3)
                    ->options([
                        'COLETIVO'      => 'Coletivo',
                        'INDIVIDUAL'    => 'Individual',
                    ])
                    ->default('INDIVIDUAL')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('descricao')
                    ->label('Descrição'),
                Tables\Columns\TextColumn::make('peso')
                    ->numeric(),
                Tables\Columns\TextColumn::make('tipo'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Deletado em')
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
            ManagersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIndicators::route('/'),
            'create' => Pages\CreateIndicator::route('/create'),
            'edit' => Pages\EditIndicator::route('/{record}/edit'),
        ];
    }
}
