<?php

namespace App\Filament\Resources\CareerPostResource\Pages;

use App\Filament\Resources\CareerPostResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCareerPosts extends ListRecords
{
    protected static string $resource = CareerPostResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
