<?php

namespace App\Filament\Resources\GiftOrderResource\Pages;

use App\Filament\Resources\GiftOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGiftOrders extends ListRecords
{
    protected static string $resource = GiftOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
