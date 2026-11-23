<?php

namespace App\Filament\Resources\GuestResource\RelationManagers;

use App\Filament\Resources\GuestResource;
use App\Models\Guest;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DependentesRelationManager extends RelationManager
{
    protected static string $relationship = 'dependentes';

    protected static ?string $title = 'Pode confirmar a presença de';

    protected static ?string $recordTitleAttribute = 'nome_completo';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nome_completo')
            ->emptyStateHeading('Ninguém delegado ainda')
            ->emptyStateDescription('Adicione os convidados cuja presença esta pessoa pode confirmar.')
            ->columns([
                Tables\Columns\TextColumn::make('nome_completo')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => GuestResource::tipoOptions()[$state] ?? $state),
                Tables\Columns\IconColumn::make('presenca_confirmada')
                    ->label('Presença')
                    ->boolean()
                    ->getStateUsing(fn (Guest $record): bool => $record->presenca_confirmada === 'S'),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Adicionar pessoa')
                    ->recordSelectSearchColumns(['nome_completo'])
                    ->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('Remover'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
