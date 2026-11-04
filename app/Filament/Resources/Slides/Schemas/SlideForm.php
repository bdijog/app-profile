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
                Section::make('Informasi Slide')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Slide')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label('Deskripsi / Teks Pendukung')
                            ->rows(3),
                        FileUpload::make('image')
                            ->label('Gambar Slide')
                            ->image()
                            ->directory('slides')
                            ->visibility('public')
                            ->required(),
                        TextInput::make('link_url')
                            ->label('Link URL (opsional)')
                            ->url()
                            ->maxLength(255),
                        Grid::make(3)->schema([
                            TextInput::make('sort_order')
                                ->label('Urutan')
                                ->numeric()
                                ->default(0),
                            DatePicker::make('start_date')
                                ->label('Mulai Tayang'),
                            DatePicker::make('end_date')
                                ->label('Selesai Tayang'),
                        ]),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ]);
    }
}
