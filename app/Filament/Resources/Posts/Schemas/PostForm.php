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
                        Section::make('Konten Artikel')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul Artikel')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                Textarea::make('summary')
                                    ->label('Ringkasan (Summary)')
                                    ->rows(3)
                                    ->helperText('Ringkasan singkat yang akan tampil pada kartu artikel.')
                                    ->columnSpanFull(),
                                RichEditor::make('content')
                                    ->label('Isi Konten')
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(2),

                    Group::make([
                        Section::make('Publikasi & Kategori')
                            ->schema([
                                Select::make('status')
                                    ->label('Status')
                                    ->options(PostStatus::class)
                                    ->default(PostStatus::Draft->value)
                                    ->required(),
                                Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload(),
                                DateTimePicker::make('published_at')
                                    ->label('Tanggal Publikasi')
                                    ->default(now()),
                                Toggle::make('is_featured')
                                    ->label('Artikel Unggulan')
                                    ->helperText('Tampilkan di bagian headline/highlight.'),
                            ]),

                        Section::make('Gambar Utama')
                            ->schema([
                                FileUpload::make('image')
                                    ->label('Foto / Cover')
                                    ->image()
                                    ->directory('posts')
                                    ->visibility('public'),
                            ]),
                    ])->columnSpan(1),
                ])->columnSpanFull(),
            ]);
    }
}
