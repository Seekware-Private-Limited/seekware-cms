<?php

namespace App\Filament\Resources\ServicePageResource\Pages;

use App\Filament\Resources\ServicePageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateServicePage extends CreateRecord
{
    protected static string $resource = ServicePageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        $recipient = auth()->user();
        return Notification::make()
            ->success()
            ->title('Service Page Created')
            ->body('New Service Page has been created successfully.')->sendToDatabase($recipient);
    }
}
