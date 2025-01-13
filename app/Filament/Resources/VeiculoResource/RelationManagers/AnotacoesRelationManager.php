<?php

namespace App\Filament\Resources\VeiculoResource\RelationManagers;

use App\Enums\{Prioridade, StatusDiversos};
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
                    ->searchable()
                    ->columnSpan(4)
                    ->relationship('itemManutencao', 'descricao')
                    ->default(null),
                Forms\Components\Textarea::make('observacao')
                    ->columnSpan(8)
                    ->label('Observação')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('itemmanutencao.descricao'),
                Tables\Columns\TextColumn::make('observacao')
                    ->label('Observação'),
                Tables\Columns\TextColumn::make('data_referencia')
                    ->date(),
                Tables\Columns\TextColumn::make('tipo_anotacao')
                    ->label('Tipo'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('prioridade'),
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
