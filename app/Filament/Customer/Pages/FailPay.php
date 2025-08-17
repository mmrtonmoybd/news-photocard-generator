<?php

namespace App\Filament\Customer\Pages;

use Filament\Pages\Page;

class FailPay extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.customer.pages.fail-pay';
}
