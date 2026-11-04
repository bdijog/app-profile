<?php

namespace App\Filament\Resources\Menus\Schemas;

use App\Enums\MenuItemType;
use App\Enums\MenuLocation;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Menu Information'))
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label(__('Menu Name'))
                                ->required()
                                ->maxLength(255),
                            Select::make('location')
                                ->label(__('Menu Location'))
                                ->options(MenuLocation::class)
                                ->required(),
                        ]),
                        Toggle::make('is_active')
                            ->label(__('Active'))
                            ->default(true),
                    ]),

                Section::make(__('Menu Items'))
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                TextInput::make('label')
                                    ->label(__('Label'))
                                    ->required(),
                                Select::make('type')
                                    ->label(__('Link Type'))
                                    ->options(MenuItemType::class)
                                    ->default(MenuItemType::Custom->value)
                                    ->required(),
                                TextInput::make('url')
                                    ->label(__('URL / Path'))
                                    ->placeholder(__('/about-us or https://...'))
                                    ->required(),
                                TextInput::make('sort_order')
                                    ->label(__('Sort Order'))
                                    ->numeric()
                                    ->default(0),
                                Toggle::make('is_active')
                                    ->label(__('Active'))
                                    ->default(true),
                            ])
                            ->columns(5)
                            ->orderColumn('sort_order')
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
