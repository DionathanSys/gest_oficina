<?php

namespace App\Filament\Resources;

use App\Enums\StatusDiversos;
use App\Enums\StatusOrdemSankhya;
use App\Filament\Resources\OrdemServicoResource\Pages;
use App\Filament\Resources\OrdemServicoResource\RelationManagers;
use App\Models\OrdemServico;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdemServicoResource extends Resource
{
    protected static ?string $model = OrdemServico::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Mant.';

    protected static ?string $pluralModelLabel = 'Ordens de Serviço';

    protected static ?string $pluralLabel = 'Ordens de Serviço';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nro_ordem')
                    ->label('Nro.OS'),
                    Forms\Components\Select::make('veiculo_id')
                        ->relationship('veiculo', 'placa')
                        ->searchable()
                        ->preload()
                        ->label('Veículo'),
                    Forms\Components\Select::make('tipo_manutencao')
                        ->options([
                            'CORRETIVA'     => 'CORRETIVA',
                            'PREVENTIVA'    => 'PREVENTIVA',
                            'PREDITIVA'     => 'PREDITIVA',
                            'SOCORRO'       => 'SOCORRO',
                        ])
                        ->default('CORRETIVA')
                        ->label('Tipo de Manutenção'),
                    Forms\Components\Select::make('status')
                        ->options(fn() => StatusDiversos::toSelectArray())
                        ->default(StatusDiversos::PENDENTE),
                    Forms\Components\Select::make('status_sankhya')
                        ->options(fn() => StatusOrdemSankhya::toSelectArray())
                        ->default(StatusDiversos::PENDENTE),
                    Forms\Components\DatePicker::make('data_abertura')
                        ->label('Data de Abertura')
                        /* ->format('d/m/Y') */
                        ->default(now()),
                    Forms\Components\DatePicker::make('data_encerramento')
                        ->label('Data de Encerramento')
                        /* ->format('d/m/Y') */,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('nro_ordem')
                    ->label('Nro.OS'),
                Tables\Columns\TextColumn::make('veiculo.placa'),
                Tables\Columns\TextColumn::make('tipo_manutencao'),
                Tables\Columns\TextColumn::make('data_abertura'),
                Tables\Columns\TextColumn::make('data_encerramento'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('status_sankhya'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->label('Inserido Em'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->label('Editado Em'),
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
            'index' => Pages\ListOrdemServicos::route('/'),
            // 'create' => Pages\CreateOrdemServico::route('/create'),
            'edit' => Pages\EditOrdemServico::route('/{record}/edit'),
        ];
    }
}
