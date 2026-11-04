<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Setting Configuration'))
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('group')
                                ->label(__('Group'))
                                ->default('general')
                                ->required(),
                            TextInput::make('key')
                                ->label(__('Key'))
                                ->required()
                                ->unique(ignoreRecord: true),
                            Select::make('type')
                                ->label(__('Value Type'))
                                ->options([
                                    'text' => __('Short Text'),
                                    'textarea' => __('Long Text'),
                                    'image' => __('Image URL / Path'),
                                    'boolean' => __('Boolean (True/False)'),
                                    'json' => __('JSON'),
                                ])
                                ->default('text')
                                ->required(),
                        ]),
                        Textarea::make('value')
                            ->label(__('Value'))
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
