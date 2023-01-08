<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\Blog\Post;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Str;

class BlogPostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationGroup = 'Blogs';

    protected static ?string $navigationIcon = 'heroicon-o-rss';

    public static function form(Form $form): Form
    {
        // return $form
        //     ->schema([
        //         Forms\Components\Group::make()
        //         ->schema([
        //             Card::make()
        //                 ->schema([
        //                     Forms\Components\TextInput::make('title')
        //                         ->required()
        //                         ->lazy()
        //                         ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null),
        //                     Forms\Components\TextInput::make('slug')
        //                         ->required()
        //                         ->unique(Post::class, 'slug', ignoreRecord: true),
        //                     Forms\Components\RichEditor::make('content')->required(),
        //                     Forms\Components\Select::make('category_id')->relationship('category', 'name')->required(),
        //                     Forms\Components\FileUpload::make('featured_image')->image()->required(),
        //                     Forms\Components\TagsInput::make('tags'),
        //                     // Forms\Components\KeyValue::make('meta')
        //                 ])->columns(2),
        //         ])
        //     ]);

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
                                    ->disabled()
                                    ->required()
                                    ->unique(Post::class, 'slug', ignoreRecord: true),

                                Forms\Components\RichEditor::make('content')
                                ->required()
                                    ->columnSpan('full'),

                                Forms\Components\Select::make('category_id')->relationship('category', 'name')->required(),

                                Forms\Components\DatePicker::make('published_at')
                                ->label('Published Date')->required(),

                                Forms\Components\TagsInput::make('tags')->columnSpanFull(),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Featured Image *')
                            ->schema([
                                Forms\Components\FileUpload::make('featured_image')
                                    ->label('Featured Image')
                                    ->image()
                                    ->required(),
                            ])
                            ->collapsible(),
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
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('slug')->sortable()->searchable(),
                Tables\Columns\TagsColumn::make('tags.name')->separator(',')
            ])
            ->filters([
                //
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
