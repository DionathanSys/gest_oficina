<?php

namespace App\Filament\Resources\VeiculoResource\RelationManagers;

use App\Enums\{Prioridade, StatusDiversos, TipoAnotacao};
use App\Filament\Resources\ItemManutencaoResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnotacoesRelationManager extends RelationManager
{
    protected static string $relationship = 'anotacoes';

    public function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Forms\Components\DatePicker::make('data_referencia')
                    ->columnSpan(2)
                    ->default(now())
                    ->displayFormat('d/m/Y')
                    ->closeOnDateSelection()
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('tipo_anotacao')
                    ->columnSpan(3)
                    ->label('Tipo Anotação')
                    ->options(function () {
                        return collect(TipoAnotacao::cases())
                            ->mapWithKeys(fn($prioridade) => [$prioridade->value => $prioridade->value])
                            ->toArray();
                    })
                    ->default(TipoAnotacao::OBSERVACAO)
                    ->required(),
                Forms\Components\Select::make('status')
                    ->columnSpan(3)
                    ->options(function () {
                        return collect(StatusDiversos::cases())
                            ->mapWithKeys(fn($prioridade) => [$prioridade->value => $prioridade->value])
                            ->toArray();
                    })
                    ->default(StatusDiversos::PENDENTE)
                    ->required(),
                Forms\Components\Select::make('prioridade')
                    ->columnSpan(3)
                    ->required()
                    ->options(function () {
                        return collect(Prioridade::cases())
                            ->mapWithKeys(fn($prioridade) => [$prioridade->value => $prioridade->value])
                            ->toArray();
                    })
                    ->default(Prioridade::BAIXA),
                Forms\Components\Select::make('item_manutencao_id')
                    ->label('Item')
                    ->searchable()
                    ->preload()
                    ->columnSpan(6)
                    ->columnStart(1)
                    ->relationship('itemManutencao', 'descricao')
                    ->default(null)
                    ->createOptionForm([
                        ItemManutencaoResource::getDescricaoFormField(),
                        ItemManutencaoResource::getComplementoFormField(),
                        ItemManutencaoResource::getAtivoFormField(),
                    ]),
                Forms\Components\Textarea::make('observacao')
                    ->columnSpanFull()
                    ->label('Observação')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->modifyQueryUsing(fn($query) => $query->with('veiculo', 'itemManutencao'))
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('itemmanutencao.descricao')
                    ->label('Item'),
                Tables\Columns\TextColumn::make('observacao')
                    ->label('Observação'),
                Tables\Columns\TextColumn::make('data_referencia')
                    ->label('Data Ref.')
                    ->date('d/m/Y'),
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
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
