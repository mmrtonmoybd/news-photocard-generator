<?php

namespace App\Filament\Admin\Resources;

use BezhanSalleh\FilamentShield\Support\Utils;

class RoleResource extends \BezhanSalleh\FilamentShield\Resources\RoleResource
{
    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.group');
    }

    public static function getNavigationIcon(): string
    {
        return __('admin.nav.icon');
    }

    public static function getNavigationSort(): ?int
    {
        return Utils::getResourceNavigationSort();
    }
}
