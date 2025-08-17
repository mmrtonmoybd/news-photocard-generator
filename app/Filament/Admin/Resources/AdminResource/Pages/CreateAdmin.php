<?php

namespace App\Filament\Admin\Resources\AdminResource\Pages;

use App\Filament\Admin\Resources\AdminResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends CreateRecord
{
    protected static string $resource = AdminResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Hash::make(rand(100000, 999999999));

        return $data;
    }

    protected function afterCreate(): void
    {
        $user = $this->record;
        $user->markEmailAsVerified();
        $token = app('auth.password.broker')->createToken($user);
        $notification = new \Filament\Notifications\Auth\ResetPassword($token);
        $notification->url = \Filament\Facades\Filament::getResetPasswordUrl($token, $user);
        $user->notify($notification);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
