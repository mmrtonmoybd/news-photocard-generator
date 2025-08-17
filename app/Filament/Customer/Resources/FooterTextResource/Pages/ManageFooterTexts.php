<?php

namespace App\Filament\Customer\Resources\FooterTextResource\Pages;

use App\Filament\Customer\Resources\FooterTextResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFooterTexts extends ManageRecords
{
    protected static string $resource = FooterTextResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
