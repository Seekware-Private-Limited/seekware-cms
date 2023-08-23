<?php

namespace App\Filament\Resources\CareerSkillResource\Pages;

use App\Filament\Resources\CareerSkillResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCareerSkills extends ListRecords
{
    protected static string $resource = CareerSkillResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
