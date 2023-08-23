<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CareerSkillResource\Pages;
use App\Models\Career\Skill;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Str;
use Closure;

class CareerSkillResource extends Resource
{
    protected static ?string $model = Skill::class;

    protected static ?string $navigationGroup = 'Career & Skill';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                        ->afterStateUpdated(function (Closure $get, Closure $set, ?string $state) {
                            if (!$get('is_slug_changed_manually') && filled($state)) {
                                $set(
                                    'slug',
                                    Str::slug($state)
                                );
                            }
                        })
                            ->reactive()
                            ->required(),
                        Forms\Components\TextInput::make('slug')
                        ->afterStateUpdated(function (Closure $set) {
                            $set('is_slug_changed_manually', true);
                        })
                            ->required()->unique(ignoreRecord: true)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('slug')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCareerSkills::route('/'),
            'create' => Pages\CreateCareerSkill::route('/create'),
            'edit' => Pages\EditCareerSkill::route('/{record}/edit'),
        ];
    }
}
