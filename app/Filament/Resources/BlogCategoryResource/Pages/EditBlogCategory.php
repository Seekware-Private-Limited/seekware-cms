<?php

namespace App\Filament\Resources\BlogCategoryResource\Pages;

use App\Filament\Resources\BlogCategoryResource;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBlogCategory extends EditRecord
{
    protected static string $resource = BlogCategoryResource::class;

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
            ->title('Blog Category Updated')
            ->body('The Blog category data has been updated successfully.');
    }

}
