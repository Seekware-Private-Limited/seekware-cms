<?php

namespace App\Filament\Resources\CareerPostResource\Pages;

use App\Filament\Resources\CareerPostResource;
use App\Models\Career\Skill;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditCareerPost extends EditRecord
{
    protected static string $resource = CareerPostResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach ($this->getRecord()->skills as $skill) {
            $data['skills'][] = $skill->name;
        }
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);
        $skills = [];
        foreach ($data['skills'] as $key => $value) {
            $skills[] = Skill::firstOrCreate(['name' => $value])->id;
        }
        $record->skills()->sync($skills);
        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Career Post Updated')
            ->body('The Career Post data has been updated successfully.');
    }
}
