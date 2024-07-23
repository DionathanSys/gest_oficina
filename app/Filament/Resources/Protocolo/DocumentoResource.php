<?php

namespace App\Filament\Resources\Protocolo;

use App\Actions\GerarProtocolo;
use App\Filament\Resources\Protocolo\DocumentoResource\Pages;
use App\Filament\Resources\Protocolo\DocumentoResource\RelationManagers;
use App\Filament\Resources\Protocolo\DocumentoResource\RelationManagers\NotasFiscaisRelationManager;
use App\Models\Protocolo\Documento;
use Carbon\Carbon;
use Filament\Tables\Actions\{
    ActionGroup,
    DeleteAction,
    EditAction,
    ViewAction
};
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\{
    BulkAction,
    DeleteBulkAction
};
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Expr\Ternary;

class DocumentoResource extends Resource
{
    protected static ?string $model = Documento::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nro_documento')
                    ->autocomplete(false)
                    ->required(),
                Forms\Components\TextInput::make('valor')
                    ->autocomplete(false)
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
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('modo_envio')
                    ->native(false)
                    ->options([
                        'malote' => 'Malote',
                        'email' => 'E-mail'
                    ])
                    ->default('malote'),
                Forms\Components\DatePicker::make('vencimento')
                    ->required()
                    ->default(now()),
                Forms\Components\DatePicker::make('envio'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) =>
                $action->slideOver()
            )
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
                            ->numeric(decimalPlaces: 2, locale: 'pt-BR')
                    ),
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
                    ->default('malote'),
                Filter::make('vencimento')
                    ->form([
                        DatePicker::make('vencimento')->label('Data Vencimento'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->where(function (Builder $query) use ($data) {

                            if (!empty($data['vencimento'])) {
                                $query->Where('vencimento', $data['vencimento']);
                            }
                        });
                    }),
                Filter::make('envio')
                    ->label('Envio Pendente')
                    ->toggle()
                    ->query(fn (Builder $query): Builder => $query->where('envio', null))
                    ->default()
            ])
            ->persistFiltersInSession()
            ->deselectAllRecordsWhenFiltered(false)
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('confirma_envio')
                        ->label('Envio Realizado')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => GerarProtocolo::exec($records))
                        ->icon('heroicon-o-calendar')
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('cancela_envio')
                        ->label('Envio Pendente')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each(function ($record) {
                            $record->update(['envio' => null]);
                        }))
                        ->icon('heroicon-o-calendar')
                        ->deselectRecordsAfterCompletion(),
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
