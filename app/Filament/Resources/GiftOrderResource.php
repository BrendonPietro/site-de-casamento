<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GiftOrderResource\Pages;
use App\Models\GiftOrder;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GiftOrderResource extends Resource
{
    protected static ?string $model = GiftOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Presenteados';

    protected static ?string $modelLabel = 'presente recebido';

    protected static ?string $pluralModelLabel = 'presentes recebidos';

    protected static ?int $navigationSort = 3;

    /**
     * Registros de pagamento são gerados pelo fluxo do Mercado Pago — o painel só consulta.
     */
    public static function canCreate(): bool
    {
        return false;
    }

    public static function statusOptions(): array
    {
        return [
            'P' => 'Pendente',
            'A' => 'Aprovado',
            'R' => 'Recusado',
            'E' => 'Expirado',
            'C' => 'Cancelado',
        ];
    }

    public static function statusColor(string $state): string
    {
        return match ($state) {
            'A' => 'success',
            'P' => 'warning',
            'R' => 'danger',
            default => 'gray',
        };
    }

    public static function paymentLabel(?string $state): string
    {
        return match ($state) {
            'pix' => 'Pix',
            'credit_card' => 'Cartão de crédito',
            'debit_card' => 'Cartão de débito',
            'account_money' => 'Saldo Mercado Pago',
            'ticket', 'bolbradesco', 'pec' => 'Boleto',
            null, '' => '—',
            default => $state,
        };
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('guest_name')
                    ->label('Quem presenteou')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gift.nome')
                    ->label('Presente')
                    ->searchable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('total_value')
                    ->label('Valor')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Pagamento')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => static::paymentLabel($state)),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => static::statusOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => static::statusColor($state)),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(static::statusOptions()),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Forma de pagamento')
                    ->options([
                        'pix' => 'Pix',
                        'credit_card' => 'Cartão de crédito',
                        'debit_card' => 'Cartão de débito',
                        'account_money' => 'Saldo Mercado Pago',
                        'ticket' => 'Boleto',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Presente')
                    ->columns(2)
                    ->schema([
                        Infolists\Components\TextEntry::make('gift.nome')
                            ->label('Presente')
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('total_value')
                            ->label('Valor total')
                            ->money('BRL'),
                        Infolists\Components\TextEntry::make('unit_value')
                            ->label('Valor unitário')
                            ->money('BRL'),
                        Infolists\Components\TextEntry::make('quantity')
                            ->label('Cotas')
                            ->placeholder('—'),
                    ]),

                Infolists\Components\Section::make('Quem presenteou')
                    ->columns(2)
                    ->schema([
                        Infolists\Components\TextEntry::make('guest_name')
                            ->label('Nome'),
                        Infolists\Components\TextEntry::make('guest_email')
                            ->label('E-mail')
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('message')
                            ->label('Mensagem')
                            ->placeholder('Sem mensagem')
                            ->columnSpanFull(),
                    ]),

                Infolists\Components\Section::make('Pagamento')
                    ->columns(2)
                    ->schema([
                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => static::statusOptions()[$state] ?? $state)
                            ->color(fn (string $state): string => static::statusColor($state)),
                        Infolists\Components\TextEntry::make('payment_method')
                            ->label('Forma de pagamento')
                            ->formatStateUsing(fn (?string $state): string => static::paymentLabel($state)),
                        Infolists\Components\TextEntry::make('mp_payment_id')
                            ->label('ID pagamento (MP)')
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('external_reference')
                            ->label('Referência')
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('paid_at')
                            ->label('Pago em')
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Criado em')
                            ->dateTime('d/m/Y H:i'),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGiftOrders::route('/'),
        ];
    }
}
