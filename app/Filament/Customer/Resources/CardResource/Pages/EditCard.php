<?php

namespace App\Filament\Customer\Resources\CardResource\Pages;

use App\Filament\Customer\Resources\CardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Card;

class EditCard extends EditRecord
{
    protected static string $resource = CardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download')->url(fn (Card $record) => route('filament.customer.resources.cards.download', $record)),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
