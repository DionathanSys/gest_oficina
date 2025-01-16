<?php

namespace App\Filament\Resources;

use App\Enums\Prioridade;
use App\Enums\StatusDiversos;
use App\Enums\TipoAnotacao;
use App\Filament\Resources\AnotacaoVeiculoResource\Pages;
use App\Filament\Resources\AnotacaoVeiculoResource\RelationManagers;
use App\Models\AnotacaoVeiculo;
use Filament\Forms;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Expr\FuncCall;

class AnotacaoVeiculoResource extends Resource
{
    protected static ?string $model = AnotacaoVeiculo::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationGroup = 'Mant.';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Acompanhamento Mant.';

    protected static ?string $label = 'Acompanhamento Mant.';

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
                        return collect(TipoAnotacao::cases())
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
                    ->preload()
                    ->searchable()
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
            ->modifyQueryUsing(function($query) {
                return $query->with('veiculo', 'itemManutencao');
            })
            ->columns([
                Tables\Columns\TextColumn::make('veiculo.placa')
                    ->label('Placa')
                    ->numeric()
                    ->searchable(isIndividual:true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('itemManutencao.descricao')
                    ->label('Item')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('observacao')
                    ->label('Observação')
                    ->searchable(),
                Tables\Columns\TextColumn::make('data_referencia')
                    ->label('Data Ref.')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\SelectColumn::make('tipo_anotacao')
                    ->options(function () {
                        return collect(TipoAnotacao::cases())
                            ->mapWithKeys(fn($prioridade) => [$prioridade->value => $prioridade->value])
                            ->toArray();
                    })
                    ->label('Tipo'),
                Tables\Columns\SelectColumn::make('status')
                    ->options(function () {
                        return collect(StatusDiversos::cases())
                            ->mapWithKeys(fn($prioridade) => [$prioridade->value => $prioridade->value])
                            ->toArray();
                    }),
                Tables\Columns\SelectColumn::make('prioridade')
                    ->options(function () {
                        return collect(Prioridade::cases())
                            ->mapWithKeys(fn($prioridade) => [$prioridade->value => $prioridade->value])
                            ->toArray();
                    }),
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
                SelectFilter::make('tipo_anotacao')
                    ->options(function(){
                        return collect(TipoAnotacao::cases())
                            ->mapWithKeys(fn($tipo_anotacao) => [$tipo_anotacao->value => $tipo_anotacao->value])
                            ->toArray();
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->groups([
                'tipo_anotacao',
                'prioridade',
                'status',
                'veiculo.placa'
            ])
            ->defaultGroup('tipo_anotacao');
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
