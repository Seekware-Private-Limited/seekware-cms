<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Card;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Str;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $navigationGroup = 'Page & Layout';
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
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
                            ->required()->unique(ignoreRecord: true),
                        TinyEditor::make('content')->required(),
                        Forms\Components\TextInput::make('meta_title'),
                        Forms\Components\TextInput::make('meta_description'),
                        Forms\Components\Toggle::make('is_published')->label('Is Published')->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('slug')->sortable()->searchable(),
                Tables\Columns\IconColumn::make('is_published')->boolean()->sortable(),
            ])
            ->filters([
            Filter::make('is_published')->toggle()->query(fn (Builder $query): Builder => $query->where('is_published', 1)),
                // SelectFilter::make('status')
                // ->multiple()
                //     ->options([
                //         'draft' => 'Draft',
                //         'reviewing' => 'Reviewing',
                //         'published' => 'Published',
                //     ]),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['from'] ?? null) {
                            $indicators['from'] = 'Created from ' . Carbon::parse($data['from'])->toFormattedDateString();
                        }

                        if ($data['until'] ?? null) {
                            $indicators['until'] = 'Created until ' . Carbon::parse($data['until'])->toFormattedDateString();
                        }

                        return $indicators;
                    })
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
