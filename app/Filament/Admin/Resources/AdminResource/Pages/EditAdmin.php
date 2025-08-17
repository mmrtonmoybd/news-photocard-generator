<?php

namespace App\Filament\Admin\Resources\AdminResource\Pages;

use App\Filament\Admin\Resources\AdminResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class EditAdmin extends EditRecord
{
    protected static string $resource = AdminResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        // Check if the current user is trying to edit their own account
        if ($this->record->id === auth()->id()) {
            Notification::make()
                ->danger()
                ->title(__('Access Denied'))
                ->body(__('You cannot edit your own profile here.'))
                ->persistent()
                ->send();

            // Redirect to the index page
            redirect()->to(static::getResource()::getUrl('index'));
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function ($action, $record) {
                    if ($record->id == auth()->id()) {
                        Notification::make()
                            ->danger()
                            ->title(__("Can't Deleted"))
                            ->body(__("You can't delete your account yourself!"))
                            ->persistent()
                            ->send();
                        $action->cancel();
                    }
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string|Htmlable
    {
        $title = $this->record->name;
        $badge = $this->getBadgeStatus();

        return new HtmlString("
            <div class='flex items-center space-x-2'>
                <div>$title</div>
                $badge
            </div>
        ");
    }

    public function getBadgeStatus(): string|Htmlable
    {
        if ($this->record->is_active) {
            $badge = "<span class='inline-flex items-center px-2 py-1 text-xs font-semibold rounded-md text-success-700 bg-success-50 ring-1 ring-inset ring-success-600/20'>Active</span>";
        } else {
            $badge = "<span class='inline-flex items-center px-2 py-1 text-xs font-semibold rounded-md text-danger-700 bg-danger-50 ring-1 ring-inset ring-danger-600/20'>Inactive</span>";
        }

        return $badge;
    }
}
