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
                Section::make('Konfigurasi Pengaturan')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('group')
                                ->label('Grup')
                                ->default('general')
                                ->required(),
                            TextInput::make('key')
                                ->label('Kunci (Key)')
                                ->required()
                                ->unique(ignoreRecord: true),
                            Select::make('type')
                                ->label('Tipe Nilai')
                                ->options([
                                    'text' => 'Teks Singkat',
                                    'textarea' => 'Teks Panjang',
                                    'image' => 'URL Gambar / Path',
                                    'boolean' => 'Boolean (True/False)',
                                    'json' => 'JSON',
                                ])
                                ->default('text')
                                ->required(),
                        ]),
                        Textarea::make('value')
                            ->label('Nilai (Value)')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
