<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\Blog\Post;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class BlogPostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationGroup = 'Blogs';

    protected static ?string $navigationIcon = 'heroicon-o-rss';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->lazy()
                                    ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null),

                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->unique(Post::class, 'slug', ignoreRecord: true),

                                Forms\Components\Select::make('category_id')->relationship('category', 'name')->required()
                                    ->columnSpan('full')->hidden(fn (?Post $record) => $record !== null),

                                Forms\Components\TextInput::make('highlight_text')->columnSpanFull(),

                                Forms\Components\RichEditor::make('content')
                                    ->required()
                                    ->columnSpan('full'),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Featured Image *')
                            ->schema([
                                Forms\Components\FileUpload::make('featured_image')
                                    ->label('Featured Image')
                                    ->image()->disk('s3')
                                    ->directory('assets/images')
                                    ->visibility('public')
                                    ->required(),
                            ])
                            ->collapsible(),
                        Forms\Components\Section::make('Meta Information')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')->columnSpanFull(),
                                Forms\Components\TextInput::make('meta_description')->columnSpanFull(),
                            ])
                            ->collapsible(),
                        Forms\Components\Section::make('Additional Information')
                            ->schema([
                                Forms\Components\TagsInput::make('tags')->columnSpanFull(),
                                Forms\Components\DateTimePicker::make('published_at')->columnSpanFull(),
                                Forms\Components\Select::make('author_id')->relationship('author', 'name')->columnSpanFull(),
                            ])
                            ->collapsible()->hidden(fn (?Post $record) => $record !== null),
                    ])
                    ->columnSpan(['lg' => fn (?Post $record) => $record === null ? 3 : 2]),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (Post $record): ?string => $record->created_at?->diffForHumans()),
                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (Post $record): ?string => $record->updated_at?->diffForHumans()),
                        Forms\Components\Select::make('category_id')->relationship('category', 'name')->required(),
                        Forms\Components\Select::make('author_id')->relationship('author', 'name')->columnSpanFull(),
                        Forms\Components\DatePicker::make('published_at')
                            ->label('Published Date')->required(),
                        Forms\Components\TagsInput::make('tags')->columnSpanFull(),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Post $record) => $record === null),
            ])
            ->columns([
                'sm' => 3,
                'lg' => null,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('title')->sortable()->searchable()->limit(30)->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                    $state = $column->getState();

                    if (strlen($state) <= $column->getLimit()) {
                        return null;
                    }

                    // Only render the tooltip if the column contents exceeds the length limit.
                    return $state;
                }),
                Tables\Columns\TextColumn::make('category.name')->sortable()->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->getStateUsing(fn (Post $record): string => $record->published_at && $record->published_at->isPast() ? 'Published' : 'Draft')
                    ->colors([
                        'success' => 'Published',
                    ]),
                Tables\Columns\TagsColumn::make('tags.name')->separator(',')
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
