<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Enums\PostStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Group::make([
                        Section::make(__('Article Content'))
                            ->schema([
                                TextInput::make('title')
                                    ->label(__('Article Title'))
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                                TextInput::make('slug')
                                    ->label(__('Slug'))
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                Textarea::make('summary')
                                    ->label(__('Summary'))
                                    ->rows(3)
                                    ->helperText(__('Brief summary to display on the article card.'))
                                    ->columnSpanFull(),
                                RichEditor::make('content')
                                    ->label(__('Content'))
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(2),

                    Group::make([
                        Section::make(__('Publication & Category'))
                            ->schema([
                                Select::make('status')
                                    ->label(__('Status'))
                                    ->options(PostStatus::class)
                                    ->default(PostStatus::Draft->value)
                                    ->required(),
                                Select::make('category_id')
                                    ->label(__('Category'))
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload(),
                                DateTimePicker::make('published_at')
                                    ->label(__('Publication Date'))
                                    ->default(now()),
                                Toggle::make('is_featured')
                                    ->label(__('Featured Article'))
                                    ->helperText(__('Display in headline/highlight section.')),
                            ]),

                        Section::make(__('Main Image'))
                            ->schema([
                                FileUpload::make('image')
                                    ->label(__('Photo / Cover'))
                                    ->image()
                                    ->directory('posts')
                                    ->visibility('public'),
                            ]),
                    ])->columnSpan(1),
                ])->columnSpanFull(),
            ]);
    }
}
