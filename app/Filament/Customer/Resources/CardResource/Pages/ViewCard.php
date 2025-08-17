<?php

namespace App\Filament\Customer\Resources\CardResource\Pages;

use App\Filament\Customer\Resources\CardResource;
use App\Models\Card;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCard extends ViewRecord
{
    protected static string $resource = CardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download')->url(fn (Card $record) => route('filament.customer.resources.cards.download', $record)),
            Actions\EditAction::make(),
        ];
    }
}
