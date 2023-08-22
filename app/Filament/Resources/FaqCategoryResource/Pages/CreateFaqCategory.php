<?php

namespace App\Filament\Resources\FaqCategoryResource\Pages;

use App\Filament\Resources\FaqCategoryResource;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFaqCategory extends CreateRecord
{
    protected static string $resource = FaqCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('FAQ Category Created')
            ->body('New FAQ Category has been created successfully.');
    }
}
