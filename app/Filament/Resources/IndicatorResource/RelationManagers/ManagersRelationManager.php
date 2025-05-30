<?php

namespace App\Filament\Resources\IndicatorResource\RelationManagers;

use App\Filament\Resources\IndicatorResource;
use App\Filament\Resources\ManagerResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Livewire\before;

class ManagersRelationManager extends RelationManager
{
    protected static string $relationship = 'managers';

    protected static bool $isLazy = false;

    public function form(Form $form): Form
    {
        return IndicatorResource::form($form);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nome')
            ->heading('Gestores')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nome')
                    ->url(fn($record) => ManagerResource::getUrl('edit', ['record' => $record->manager_id]))
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('unidade'),
                Tables\Columns\TextColumn::make('setor'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make()
                //     ->label('Gestor')
                //     ->icon('heroicon-o-plus')
                //     ->form(fn(Forms\Form $form) => ManagerResource::form($form))
                //     ->modalHeading('Novo Gestor'),

                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->modalHeading('Vincular Gestor')
                    // ->form(fn (Tables\Actions\AttachAction $action): array => [
                    //     $action->getRecordSelect(),
                    //     Forms\Components\TextInput::make('nome')->required(),
                    // ])
                    ->recordSelect(
                        fn (Forms\Components\Select $select) => $select->placeholder('Selecionar Gestor'),
                    ),

            ])
            ->actions([
                // Tables\Actions\EditAction::make()
                //     ->iconButton(),
                Tables\Actions\DetachAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Sem registros')
            ->emptyStateDescription('Clique em "+ Gestor" ou "Vincular" para adicionar um gestor a este indicador.');
    }
}
