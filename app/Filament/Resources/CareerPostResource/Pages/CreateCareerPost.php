<?php

namespace App\Filament\Resources\CareerPostResource\Pages;

use App\Filament\Resources\CareerPostResource;
use App\Models\Career\Skill;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class CreateCareerPost extends CreateRecord
{
    protected static string $resource = CareerPostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // $data['author_id'] = auth()->id();

        $data['excerpt'] =  Str::excerpt($data['description']);

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $insert = static::getModel()::create($data);
        $skills = [];
        foreach ($data['skills'] as $key => $value) {
            $skills[] = Skill::firstOrCreate(['name' => $value])->id;
        }
        $insert->skills()->sync($skills);
        return $insert;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Career Post Created')
            ->body('New Career Post has been created successfully.');
    }
}
