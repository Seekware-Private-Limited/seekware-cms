<?php

namespace App\Filament\Resources\BlogPostResource\Pages;

use App\Filament\Resources\BlogPostResource;
use App\Models\Blog\Tag;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class CreateBlogPost extends CreateRecord
{
    protected static string $resource = BlogPostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // $data['author_id'] = auth()->id();

        $data['excerpt'] =  Str::words($data['content']);

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $insert = static::getModel()::create($data);
        $tags = [];
        foreach ($data['tags'] as $key => $value) {
            $tags[] = Tag::firstOrCreate(['name' => $value])->id;
        }
        $insert->tags()->sync($tags);
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
            ->title('Blog Created')
            ->body('New Blog has been created successfully.');
    }
}
