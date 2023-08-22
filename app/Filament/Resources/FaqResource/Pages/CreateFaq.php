<?php

namespace App\Filament\Resources\FaqResource\Pages;

use App\Filament\Resources\FaqResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateFaq extends CreateRecord
{
    protected static string $resource = FaqResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['excerpt'] =  Str::words($data['content']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('FAQ Created')
            ->body('New FAQ has been created successfully.');
    }
}
