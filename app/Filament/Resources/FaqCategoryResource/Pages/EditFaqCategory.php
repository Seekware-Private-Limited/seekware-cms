<?php

namespace App\Filament\Resources\FaqCategoryResource\Pages;

use App\Filament\Resources\FaqCategoryResource;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFaqCategory extends EditRecord
{
    protected static string $resource = FaqCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {

        return Notification::make()
            ->success()
            ->title('FAQ Category Updated')
            ->body('The FAQ category data has been updated successfully.');
    }
}
