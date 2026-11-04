<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Enums\PageStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Group::make([
                        Section::make(__('Page Content'))
                            ->schema([
                                TextInput::make('title')
                                    ->label(__('Page Title'))
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                                TextInput::make('slug')
                                    ->label(__('Slug'))
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                RichEditor::make('content')
                                    ->label(__('Content'))
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(2),

                    Group::make([
                        Section::make(__('Settings & Status'))
                            ->schema([
                                Select::make('status')
                                    ->label(__('Status'))
                                    ->options(PageStatus::class)
                                    ->default(PageStatus::Published->value)
                                    ->required(),
                                TextInput::make('sort_order')
                                    ->label(__('Sort Order'))
                                    ->numeric()
                                    ->default(0),
                                Toggle::make('show_in_menu')
                                    ->label(__('Show in Menu'))
                                    ->default(true),
                            ]),

                        Section::make(__('Main Image'))
                            ->schema([
                                FileUpload::make('image')
                                    ->label(__('Photo / Cover'))
                                    ->image()
                                    ->directory('pages')
                                    ->visibility('public'),
                            ]),
                    ])->columnSpan(1),
                ])->columnSpanFull(),
            ]);
    }
}
