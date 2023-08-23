<?php

namespace App\Filament\Resources\CareerSkillResource\Pages;

use App\Filament\Resources\CareerSkillResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCareerSkill extends CreateRecord
{
    protected static string $resource = CareerSkillResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('New Career Skill Created')
            ->body('New Career Skill has been created successfully.');
    }
}
