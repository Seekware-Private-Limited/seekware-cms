<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServicePageResource\Pages;
use App\Filament\Resources\ServicePageResource\RelationManagers;
use App\Models\Service\Post;
use Illuminate\Support\Carbon;
use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Card;
use Illuminate\Support\Str;
use Filament\Forms\Components\Repeater;

class ServicePageResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $modelLabel = 'Service Page';
    protected static ?string $navigationGroup = 'Page & Layout';
    protected static ?string $navigationIcon = 'heroicon-o-server';

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
                        Forms\Components\TextInput::make('subtitle')->required(),
                        Forms\Components\TextInput::make('menu_title')->required(),
                        Forms\Components\TextInput::make('service_heading')->required(),
                        Forms\Components\TextInput::make('stats_title')->required(),
                        Forms\Components\DateTimePicker::make('published_at'),
                        Forms\Components\RichEditor::make('service_description')->required(),
                        Forms\Components\Textarea::make('cta_text')->required(),
                        Forms\Components\Textarea::make('cta_feature_text')->required(),
                        Forms\Components\Textarea::make('approach_feature_text')->required(),
                        Forms\Components\FileUpload::make('bg_image')
                            ->label('Background Image')
                            ->image()->disk('s3')
                            ->directory('assets/images')
                            ->visibility('public')->required()->minSize(1)
                            ->maxSize(500)->imagePreviewHeight('100'),
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Meta Image')
                            ->image()->disk('s3')
                            ->directory('assets/images')
                            ->visibility('public')->required()->minSize(1)
                            ->maxSize(500)->imagePreviewHeight('100'),
                        Forms\Components\FileUpload::make('service_image')
                            ->label('Service Image')
                            ->image()->disk('s3')
                            ->directory('assets/images')
                            ->visibility('public')->required()->minSize(1)
                            ->maxSize(500)->imagePreviewHeight('100')->columnSpanFull(),
                        Repeater::make('features')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('title')->required(),
                                Forms\Components\TextInput::make('subtitle')->required(),
                                Forms\Components\FileUpload::make('image')
                                    ->label('Featured Image')
                                    ->image()->disk('s3')
                                    ->directory('assets/images')
                                    ->visibility('public')->required()->minSize(1)
                                    ->maxSize(300)->imagePreviewHeight('100'),
                                Forms\Components\RichEditor::make('description')->required(),
                            ])->collapsible(),
                        Repeater::make('advantages')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('title')->required(),
                                Forms\Components\FileUpload::make('image')
                                    ->label('Featured Image')
                                    ->image()->disk('s3')
                                    ->directory('assets/images')
                                    ->visibility('public')->required()->minSize(1)
                                    ->maxSize(300)->imagePreviewHeight('100'),
                                Forms\Components\RichEditor::make('description')->required(),
                            ])->collapsible(),
                        Forms\Components\TextInput::make('meta_title')->required(),
                        Forms\Components\TextInput::make('meta_description')->required(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('slug')->sortable()->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->getStateUsing(fn (Post $record): string => $record->published_at && $record->published_at->isPast() ? 'Published' : 'Draft')
                    ->colors([
                        'success' => 'Published',
                    ]),
            ])
            ->filters([
                Tables\Filters\Filter::make('published_at')
                    ->form([
                        Forms\Components\DatePicker::make('published_from'),
                        Forms\Components\DatePicker::make('published_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['published_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '>=', $date),
                            )
                            ->when(
                                $data['published_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['published_from'] ?? null) {
                            $indicators['published_from'] = 'Published from ' . Carbon::parse($data['published_from'])->toFormattedDateString();
                        }

                        if ($data['published_until'] ?? null) {
                            $indicators['published_until'] = 'Published until ' . Carbon::parse($data['published_until'])->toFormattedDateString();
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
            'index' => Pages\ListServicePages::route('/'),
            'create' => Pages\CreateServicePage::route('/create'),
            'edit' => Pages\EditServicePage::route('/{record}/edit'),
        ];
    }
}
