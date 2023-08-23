<?php

namespace App\Filament\Resources\CareerSkillResource\Pages;

use App\Filament\Resources\CareerSkillResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditCareerSkill extends EditRecord
{
    protected static string $resource = CareerSkillResource::class;

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
            ->title('Career Skill Updated')
            ->body('The Career Skill has been updated successfully.');
    }
}
