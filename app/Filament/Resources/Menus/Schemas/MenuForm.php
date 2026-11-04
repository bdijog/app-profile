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
                Section::make('Informasi Menu')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Menu')
                                ->required()
                                ->maxLength(255),
                            Select::make('location')
                                ->label('Lokasi Menu')
                                ->options(MenuLocation::class)
                                ->required(),
                        ]),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),

                Section::make('Item Menu')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                TextInput::make('label')
                                    ->label('Label')
                                    ->required(),
                                Select::make('type')
                                    ->label('Tipe Link')
                                    ->options(MenuItemType::class)
                                    ->default(MenuItemType::Custom->value)
                                    ->required(),
                                TextInput::make('url')
                                    ->label('URL / Path')
                                    ->placeholder('/tentang-kami atau https://...')
                                    ->required(),
                                TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0),
                                Toggle::make('is_active')
                                    ->label('Aktif')
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
