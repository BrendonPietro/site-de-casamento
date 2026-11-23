<?php

namespace App\Filament\Concerns;

use Filament\Forms\Components\Toggle;

trait HandlesSnBooleans
{
    /**
     * Toggle que persiste 'S'/'N' (padrão do banco) mas é exibido como booleano.
     */
    protected static function snToggle(string $name, string $label): Toggle
    {
        return Toggle::make($name)
            ->label($label)
            ->formatStateUsing(fn ($state): bool => $state === 'S')
            ->dehydrateStateUsing(fn ($state): string => $state ? 'S' : 'N')
            ->default(false);
    }
}
