<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HandlesSnBooleans;
use App\Filament\Resources\GiftResource\Pages;
use App\Models\Gift;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class GiftResource extends Resource
{
    use HandlesSnBooleans;

    protected static ?string $model = Gift::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationLabel = 'Presentes';

    protected static ?string $modelLabel = 'presente';

    protected static ?string $pluralModelLabel = 'presentes';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dados do presente')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nome')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('descricao')
                            ->label('Descrição')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('valor')
                            ->label('Valor')
                            ->numeric()
                            ->prefix('R$')
                            ->required(),
                        Forms\Components\TextInput::make('categoria')
                            ->label('Categoria')
                            ->maxLength(255)
                            ->datalist(fn (): array => Gift::query()
                                ->whereNotNull('categoria')
                                ->where('categoria', '!=', '')
                                ->distinct()
                                ->orderBy('categoria')
                                ->pluck('categoria')
                                ->all())
                            ->helperText('Escolha uma categoria existente na lista ou digite uma nova.'),
                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('gifts')
                            ->visibility('public')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Cotas e disponibilidade')
                    ->columns(2)
                    ->schema([
                        static::snToggle('cotavel', 'É cotável (vaquinha)')
                            ->live(),
                        static::snToggle('vendido', 'Vendido'),
                        Forms\Components\TextInput::make('num_cotas')
                            ->label('Número de cotas')
                            ->numeric()
                            ->minValue(1)
                            ->visible(fn (Forms\Get $get): bool => (bool) $get('cotavel')),
                        Forms\Components\TextInput::make('cotas_vendidas')
                            ->label('Cotas vendidas')
                            ->numeric()
                            ->default(0)
                            ->visible(fn (Forms\Get $get): bool => (bool) $get('cotavel')),
                        static::snToggle('reservado', 'Reservado'),
                        Forms\Components\DateTimePicker::make('reservado_ate')
                            ->label('Reservado até')
                            ->seconds(false),
                    ]),

                Forms\Components\Section::make('Exibição no site')
                    ->columns(2)
                    ->schema([
                        static::snToggle('ativo', 'Ativo')->default(true),
                        static::snToggle('favorito', 'Favorito'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public')
                    ->getStateUsing(fn (Gift $record): string => $record->foto
                        ?: 'gifts/' . Str::slug($record->nome) . '.png')
                    ->height(44)
                    ->square(),
                Tables\Columns\TextColumn::make('nome')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('valor')
                    ->label('Valor')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('categoria')
                    ->label('Categoria')
                    ->badge()
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('cotas')
                    ->label('Cotas')
                    ->getStateUsing(fn (Gift $record): string => $record->isQuotaBased()
                        ? "{$record->cotas_vendidas}/{$record->num_cotas}"
                        : '—'),
                Tables\Columns\IconColumn::make('ativo')
                    ->label('Ativo')
                    ->boolean()
                    ->getStateUsing(fn (Gift $record): bool => $record->ativo === 'S'),
                Tables\Columns\IconColumn::make('vendido')
                    ->label('Vendido')
                    ->boolean()
                    ->getStateUsing(fn (Gift $record): bool => $record->vendido === 'S')
                    ->toggleable(),
            ])
            ->defaultSort('nome')
            ->filters([
                Tables\Filters\SelectFilter::make('categoria')
                    ->label('Categoria')
                    ->options(fn (): array => Gift::query()
                        ->whereNotNull('categoria')
                        ->distinct()
                        ->orderBy('categoria')
                        ->pluck('categoria', 'categoria')
                        ->all()),
                Tables\Filters\SelectFilter::make('ativo')
                    ->label('Ativo')
                    ->options(['S' => 'Sim', 'N' => 'Não']),
                Tables\Filters\SelectFilter::make('cotavel')
                    ->label('Cotável')
                    ->options(['S' => 'Sim', 'N' => 'Não']),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGifts::route('/'),
            'create' => Pages\CreateGift::route('/create'),
            'edit' => Pages\EditGift::route('/{record}/edit'),
        ];
    }
}
