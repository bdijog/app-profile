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
                        Section::make('Konten Halaman')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul Halaman')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                RichEditor::make('content')
                                    ->label('Isi Konten')
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(2),

                    Group::make([
                        Section::make('Pengaturan & Status')
                            ->schema([
                                Select::make('status')
                                    ->label('Status')
                                    ->options(PageStatus::class)
                                    ->default(PageStatus::Published->value)
                                    ->required(),
                                TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0),
                                Toggle::make('show_in_menu')
                                    ->label('Tampilkan di Menu')
                                    ->default(true),
                            ]),

                        Section::make('Gambar Utama')
                            ->schema([
                                FileUpload::make('image')
                                    ->label('Foto / Cover')
                                    ->image()
                                    ->directory('pages')
                                    ->visibility('public'),
                            ]),
                    ])->columnSpan(1),
                ])->columnSpanFull(),
            ]);
    }
}
