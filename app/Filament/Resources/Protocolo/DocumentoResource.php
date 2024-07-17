<?php

namespace App\Filament\Resources\Protocolo;

use App\Filament\Resources\Protocolo\DocumentoResource\Pages;
use App\Filament\Resources\Protocolo\DocumentoResource\RelationManagers;
use App\Filament\Resources\Protocolo\DocumentoResource\RelationManagers\NotasFiscaisRelationManager;
use App\Models\Protocolo\Documento;
use Filament\Actions\ReplicateAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentoResource extends Resource
{
    protected static ?string $model = Documento::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nro_documento')
                    ->required(),
                Forms\Components\TextInput::make('valor')
                    ->prefix('R$')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('fornecedor_id')
                    ->relationship('fornecedor', 'nome')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nome')
                            ->required()
                            ->maxLength(255),
                            ])
                    // ->searchable()
                    // ->preload()
                    ->required(),
                Forms\Components\Select::make('modo_envio')
                    ->options([
                        'malote' => 'Malote',
                        'email' => 'E-mail'
                    ]),
                Forms\Components\DatePicker::make('vencimento')
                    ->required()
                    ->default(now()),
                Forms\Components\DatePicker::make('envio'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nro_documento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('valor')
                    ->prefix('R$')
                    ->numeric(decimalPlaces: 2, locale: 'pt-BR')
                    ->sortable()
                    ->summarize(
                        Sum::make()
                            ->label('Soma')
                            ->numeric(decimalPlaces: 2,locale: 'pt-BR')),
                Tables\Columns\TextColumn::make('vencimento')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fornecedor.nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('modo_envio')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('envio')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('modo_envio')
                    ->options([
                        'malote' => 'Malote',
                        'email' => 'E-mail'
                    ])
            ])
            ->persistFiltersInSession()
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ReplicateAction::make()
                    // ->excludeAttributes(['nro_documento', 'valor', 'vencimento', 'envio'])
                    ->mutateRecordDataUsing(function (array $data): array {
                        $data['nro_documento'] = 22;
                 
                        return $data;
                    })
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
            NotasFiscaisRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocumentos::route('/'),
            'create' => Pages\CreateDocumento::route('/create'),
            'edit' => Pages\EditDocumento::route('/{record}/edit'),
        ];
    }
}
