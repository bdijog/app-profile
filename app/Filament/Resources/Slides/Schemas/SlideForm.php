<?php

namespace App\Filament\Resources\Slides\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Slide Information'))
                    ->schema([
                        TextInput::make('title')
                            ->label(__('Slide Title'))
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label(__('Description / Supporting Text'))
                            ->rows(3),
                        FileUpload::make('image')
                            ->label(__('Slide Image'))
                            ->image()
                            ->directory('slides')
                            ->visibility('public')
                            ->required(),
                        TextInput::make('link_url')
                            ->label(__('Link URL (optional)'))
                            ->url()
                            ->maxLength(255),
                        Grid::make(3)->schema([
                            TextInput::make('sort_order')
                                ->label(__('Sort Order'))
                                ->numeric()
                                ->default(0),
                            DatePicker::make('start_date')
                                ->label(__('Start Date')),
                            DatePicker::make('end_date')
                                ->label(__('End Date')),
                        ]),
                        Toggle::make('is_active')
                            ->label(__('Active'))
                            ->default(true),
                    ]),
            ]);
    }
}
