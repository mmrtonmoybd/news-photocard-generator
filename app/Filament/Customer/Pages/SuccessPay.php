<?php

namespace App\Filament\Customer\Pages;

use Filament\Pages\Page;

class SuccessPay extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.customer.pages.success-pay';

    protected static bool $shouldRegisterNavigation = false;
}
