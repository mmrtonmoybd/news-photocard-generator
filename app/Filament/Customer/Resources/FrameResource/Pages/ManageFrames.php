<?php

namespace App\Filament\Customer\Resources\FrameResource\Pages;

use App\Filament\Customer\Resources\FrameResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFrames extends ManageRecords
{
    protected static string $resource = FrameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
