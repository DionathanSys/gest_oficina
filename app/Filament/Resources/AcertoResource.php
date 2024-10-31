<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcertoResource\Pages;
use App\Filament\Resources\AcertoResource\RelationManagers;
use App\Filament\Resources\AcertoResource\RelationManagers\ValorAjudaRelationManager;
use App\Filament\Resources\AcertoResource\RelationManagers\ViagensDuplaRelationManager;
use App\Filament\Resources\AcertoResource\RelationManagers\ViagensRelationManager;
use App\Models\Acerto;
use App\Models\ComplementoAcerto;
use App\Models\Motorista;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\Layout\Split;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AcertoResource extends Resource
{
    protected static ?string $model = Acerto::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(6)
            ->schema([
                Forms\Components\TextInput::make('motorista')
                    ->columnSpan(2)
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('fechamento')
                    ->columnSpan(1)
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('nro_acerto')
                    ->columnSpan(1)
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('motorista_id')
                    ->columnSpan(1)
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('vlr_fechamento')
                    ->label('Vlr. Fechamento')
                    ->prefix('R$')
                    ->columnSpan(1)
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('vlr_comissao')
                    ->label('Pr. Produtividade')
                    ->prefix('R$')
                    ->columnSpan(1)
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('vlr_media')
                    ->label('Pr. Média')
                    ->prefix('R$')
                    ->columnSpan(1)
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('vlr_manutencao')
                    ->label('Pr. Manutenção')
                    ->prefix('R$')
                    ->columnSpan(1)
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('vlr_inss')
                    ->label('INSS')
                    ->prefix('R$')
                    ->columnSpan(1)
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('vlr_irrf')
                    ->label('IRRF')
                    ->prefix('R$')
                    ->columnSpan(1)
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('vlr_diferenca')
                    ->label('Vlr. Diferença')
                    ->prefix('R$')
                    ->columnSpan(1)
                    ->required()
                    ->numeric(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                return $query->with(['PrSeguranca', 'valor_ajuda']);
            })
            ->columns([

                TextColumn::make('nro_acerto')
                    ->label('Acerto')
                    ->sortable()
                    ->visibleFrom('lg')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('motorista')
                    ->limit(10)
                    ->searchable()
                    ->sortable()
                /* ->wrap() */,

                TextColumn::make('fechamento')
                    ->searchable()
                    ->visibleFrom('lg')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('motorista_id')
                    ->numeric()
                    ->sortable()
                    ->copyable()
                    ->copyableState(function (Acerto $record) {
                        return $record->getComplemento();
                    })
                    ->visibleFrom('lg')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('salario_liq')
                    ->label('S. Líquido')
                    ->money('BRL')
                    ->state(function (Acerto $record) {
                        return 'R$ ' . number_format($record->getSalarioLiquido(), 2, ',', '.');
                    })
                    ->toggleable(),

                TextColumn::make('produtividade')
                    ->badge(fn(string $state): string => 'succes')
                    // ->color()
                    ->label('Produtividade')
                    ->state(function (Acerto $record) {

                        return 'R$ ' . number_format($record->getProdutividade(), 2, ',', '.');
                    })
                    ->copyable()
                    ->copyableState(function (Acerto $record) {
                        return number_format($record->getProdutividade(), 2, ',', '.');
                    })
                    ->money('BRL')
                    ->sortable()
                    ->visibleFrom('lg')
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('teste')
                    ->label('Validação')
                    ->state(function (Acerto $record) {
                        $valor = $record->vlr_diferenca + $record->getProdutividade();
                        return 'R$ ' . number_format($valor, 2, ',', '.');
                    })
                    ->toggleable(),

                TextColumn::make('vlr_fechamento')
                    ->label('Vlr Fechamento')
                    ->money('BRL', locale: 'pt_BR')
                    ->visibleFrom('lg')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('vlr_media')
                    ->label('Pr. Média')
                    ->summarize(Sum::make()->money('BRL', 100, 'pt_BR'))
                    ->money('BRL', locale: 'pt_BR')
                    ->toggleable(),

                TextInputColumn::make('vlr_manutencao')
                    ->label('Pr. Mant.')
                    // ->copyable()
                    // ->money('BRL', locale: 'pt_BR')
                    ->summarize(Sum::make()->money('BRL', 100, 'pt_BR'))
                    ->toggleable(),

                TextColumn::make('ajuda')
                    ->label('Vlr Ajuda')
                    ->money('BRL', locale: 'pt_BR')
                    ->state(fn(Acerto $record) => $record->valor_ajuda->sum('vlr_ajuda'))
                    ->toggleable(),

                TextColumn::make('seguranca')
                    ->label('Pr. Segurança')
                    ->money('BRL', locale: 'pt_BR')
                    ->state(fn(Acerto $record) => $record->PrSeguranca->premio ?? 0)
                    ->toggleable(),

                TextColumn::make('vlr_inss')
                    ->label('Vlr INSS')
                    ->money('BRL', locale: 'pt_BR')
                    ->visibleFrom('lg')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('vlr_irrf')
                    ->label('Vlr IRRF')
                    ->money('BRL', locale: 'pt_BR')
                    ->visibleFrom('lg')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('vlr_diferenca')
                    ->label('Vlr Diferença')
                    ->money('BRL', locale: 'pt_BR')
                    ->visibleFrom('lg')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('vlr_comissao')
                    ->label('Pr. Produtividade')
                    ->money('BRL', locale: 'pt_BR')
                    ->summarize(Sum::make()->money('BRL', 100, 'pt_BR'))
                    ->toggleable(),

                TextColumn::make('complementos')
                    ->view('tables.columns.complemento-acerto')
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->visibleFrom('lg')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->visibleFrom('lg')
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                SelectFilter::make('fechamento')
                    ->options(
                        [
                            '202410' => '202410',
                            '202409' => '202409',
                            '202408' => '202408',
                        ]
                        )
                    ->default('202409'),
                SelectFilter::make('motorista')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->relationship('motorista', 'nome')
            ])
            ->actions([
                Tables\Actions\Action::make('complemento')
                    ->action(
                        function ($data, Acerto $record) {
                            ComplementoAcerto::create(
                                [
                                    'acerto_id' => $record->id,
                                    'vlr_ajuda' => $data['vlr_ajuda'],
                                    'motivo' => $data['motivo']
                                ]
                            );
                        }
                    )
                    ->form([
                        Forms\Components\TextInput::make('vlr_ajuda')
                            ->numeric()
                            ->prefix('R$')
                            ->required(),
                        Forms\Components\Select::make('motivo')
                            ->required()
                            ->default('Ref. Aj. Custo')
                            ->options([
                                'Ref. Aj. Custo' => 'Ajuda de Custo',
                                'Ref. Domingo(s)' => 'Domingo',
                                'Ref. Dias de Base' => 'Dias Base',
                                'Ref. Manobra' => 'Manobra',
                                'Ref. Viagens em outro Caminhão' => 'Viagens'
                            ])
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            //  ->searchOnBlur()
             ;
    }

    public static function getRelations(): array
    {
        return [
            ValorAjudaRelationManager::class,
            ViagensRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAcertos::route('/'),
            'create' => Pages\CreateAcerto::route('/create'),
            'edit' => Pages\EditAcerto::route('/{record}/edit'),
        ];
    }
}
