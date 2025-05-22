<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IndicatorResultResource\Pages;
use App\Filament\Resources\IndicatorResultResource\RelationManagers;
use App\Models\Indicator;
use App\Models\IndicatorResult;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IndicatorResultResource extends Resource
{
    protected static ?string $model = IndicatorResult::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $pluralModelLabel = 'Resultados';

    protected static ?string $pluralLabel = 'Resultados';

    protected static ?string $label = 'Resultado';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Forms\Components\Select::make('indicator_id')
                    ->relationship('indicator', 'descricao')
                    ->columnSpan(7)
                    ->searchable()
                    ->preload()
                    ->label('Indicador')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if($state){
                            $indicator = Indicator::find($state);
                            $set('pontuacao_obtida', $indicator->peso ?? 0);
                            return;
                        }
                        $set('pontuacao_obtida', 0);
                    }),
                Forms\Components\DatePicker::make('periodo')
                    ->columnStart(1)
                    ->columnSpan(2)
                    ->label('Período')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->default(Carbon::now()->subMonth()->startOfMonth())
                    ->required(),
                Forms\Components\Select::make('resultado')
                    ->columnSpan(3)
                    ->options([
                        'ATENDIDO'      => 'Atendido',
                        'NAO_ATENDIDO'  => 'Não Atendido',
                    ])
                    ->default('NAO_ATENDIDO'),
                Forms\Components\TextInput::make('pontuacao_obtida')
                    ->columnSpan(2)
                    ->readOnly()
                    ->label('Peso')
                    ->reactive()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('indicator.descricao')
                    ->label('Descrição'),
                Tables\Columns\TextColumn::make('manager.nome')
                    ->label('Gestor'),
                Tables\Columns\TextColumn::make('periodo')
                    ->label('Período')
                    ->date(),
                Tables\Columns\TextColumn::make('resultado')
                    ->label('Resultado'),
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
            'index' => Pages\ListIndicatorResults::route('/'),
            'create' => Pages\CreateIndicatorResult::route('/create'),
            'edit' => Pages\EditIndicatorResult::route('/{record}/edit'),
        ];
    }
}
