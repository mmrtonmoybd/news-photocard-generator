<?php

namespace App\Filament\Customer\Resources\LogoResource\Pages;

use App\Filament\Customer\Resources\LogoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLogos extends ManageRecords
{
    protected static string $resource = LogoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
