<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq\Faq;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Str;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationGroup = 'FAQs';

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

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
                                    ->unique(Faq::class, 'slug', ignoreRecord: true),

                                Forms\Components\Select::make('category_id')->relationship('category', 'name')->required()
                                    ->columnSpan('full')->hidden(fn (?Faq $record) => $record !== null),

                                Forms\Components\RichEditor::make('content')
                                ->required()
                                    ->columnSpan('full'),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Additional Information')
                        ->schema([
                            Forms\Components\DateTimePicker::make('published_at')->columnSpanFull(),
                        ])
                            ->collapsible()->hidden(fn (?Faq $record) => $record !== null),
                    ])
                    ->columnSpan(['lg' => fn (?Faq $record) => $record === null ? 3 : 2]),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                        ->label('Created at')
                        ->content(fn (Faq $record): ?string => $record->created_at?->diffForHumans()),
                        Forms\Components\Placeholder::make('updated_at')
                        ->label('Last modified at')
                        ->content(fn (Faq $record): ?string => $record->updated_at?->diffForHumans()),
                        Forms\Components\Select::make('category_id')->relationship('category', 'name')->required(),
                        Forms\Components\DatePicker::make('published_at')
                        ->label('Published Date')->required(),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Faq $record) => $record === null),
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
                Tables\Columns\BadgeColumn::make('status')
                    ->getStateUsing(fn (Faq $record): string => $record->published_at && $record->published_at->isPast() ? 'Published' : 'Draft')
                    ->colors([
                        'success' => 'Published',
                    ]),
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
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
