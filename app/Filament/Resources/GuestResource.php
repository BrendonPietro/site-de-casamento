<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HandlesSnBooleans;
use App\Filament\Resources\GuestResource\Pages;
use App\Filament\Resources\GuestResource\RelationManagers\DependentesRelationManager;
use App\Models\Guest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class GuestResource extends Resource
{
    use HandlesSnBooleans;

    protected static ?string $model = Guest::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Convidados';

    protected static ?string $modelLabel = 'convidado';

    protected static ?string $pluralModelLabel = 'convidados';

    protected static ?int $navigationSort = 1;

    /**
     * Rótulos amigáveis dos tipos de convidado (enum da tabela guests).
     */
    public static function tipoOptions(): array
    {
        return [
            'convidado' => 'Convidado',
            'madrinha' => 'Madrinha',
            'padrinho' => 'Padrinho',
            'mae' => 'Mãe',
            'pai' => 'Pai',
            'avo' => 'Avô',
            'ava' => 'Avó',
            'familiar' => 'Familiar',
            'noivo' => 'Noivo',
            'noiva' => 'Noiva',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dados do convidado')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nome_completo')
                            ->label('Nome completo')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('telefone')
                            ->label('Telefone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('E-mail')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Select::make('tipo')
                            ->label('Tipo')
                            ->options(static::tipoOptions())
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('sexo')
                            ->label('Sexo')
                            ->options(['M' => 'Masculino', 'F' => 'Feminino'])
                            ->native(false),
                        Forms\Components\TextInput::make('numero_calcado')
                            ->label('Número do calçado')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(60),
                    ]),

                Forms\Components\Section::make('Presença')
                    ->columns(2)
                    ->schema([
                        static::snToggle('presenca_confirmada', 'Presença confirmada'),
                        static::snToggle('interesse_van', 'Tem interesse na van'),
                    ]),

                Forms\Components\Section::make('Senha de acesso')
                    ->description('Preencha apenas para definir ou trocar a senha de login deste convidado.')
                    ->schema([
                        Forms\Components\TextInput::make('senha')
                            ->label('Senha')
                            ->password()
                            ->revealable()
                            ->autocomplete('new-password')
                            ->dehydrated(fn ($state): bool => filled($state))
                            ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                            ->required(fn (string $operation): bool => $operation === 'create'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome_completo')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => static::tipoOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => in_array($state, ['noivo', 'noiva'], true) ? 'primary' : 'gray')
                    ->sortable(),
                Tables\Columns\IconColumn::make('presenca_confirmada')
                    ->label('Presença')
                    ->boolean()
                    ->getStateUsing(fn (Guest $record): bool => $record->presenca_confirmada === 'S'),
                Tables\Columns\IconColumn::make('interesse_van')
                    ->label('Van')
                    ->boolean()
                    ->getStateUsing(fn (Guest $record): bool => $record->interesse_van === 'S')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('numero_calcado')
                    ->label('Calçado')
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('telefone')
                    ->label('Telefone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ultimo_acesso')
                    ->label('Último acesso')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Nunca')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Última atualização')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('nome_completo')
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options(static::tipoOptions()),
                Tables\Filters\SelectFilter::make('presenca_confirmada')
                    ->label('Presença')
                    ->options(['S' => 'Confirmada', 'N' => 'Pendente']),
                Tables\Filters\SelectFilter::make('interesse_van')
                    ->label('Interesse na van')
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

    public static function getRelations(): array
    {
        return [
            DependentesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGuests::route('/'),
            'create' => Pages\CreateGuest::route('/create'),
            'edit' => Pages\EditGuest::route('/{record}/edit'),
        ];
    }
}
