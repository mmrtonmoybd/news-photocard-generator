<?php

namespace App\Filament\Admin\Pages;

use Closure;
use Filament\Forms\Components;
use Filament\Forms\Components\Tabs;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;

class Setting extends BaseSettings
{
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public function schema(): array|Closure
    {
        return [
            Tabs::make('Settings')
                ->schema([
                    Tabs\Tab::make('General')
                        ->schema([
                            Components\TextInput::make('general.brand_name')
                                ->required(),
                            Components\FileUpload::make('general.brand_logo')
                                ->image(),
                        ]),
                    Tabs\Tab::make('SSLcommerz')
                        ->schema([
                            Components\TextInput::make('SSLcommerz.storeid')
                                ->label('Store ID')
                                ->required(),
                            Components\TextInput::make('SSLcommerz.storepassword')
                                ->label('Sore passowrd')
                                ->password()
                                ->required(),
                            Components\Toggle::make('SSLcommerz.sandbox')
                                ->label('Is testing')
                                ->required()
                                ->default(false),
                            Components\Toggle::make('SSLcommerz.localhost')
                                ->required()
                                ->label('Is local server')
                                ->default(false),
                        ]),
                ]),
        ];
    }
}
