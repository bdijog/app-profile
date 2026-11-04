<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Upload Berkas')
                    ->schema([
                        FileUpload::make('file_path')
                            ->label('Pilih Berkas / Gambar')
                            ->directory('uploads')
                            ->visibility('public')
                            ->required()
                            ->storeFileNamesIn('file_name'),
                        TextInput::make('alt_text')
                            ->label('Teks Alternatif (Alt Text)')
                            ->placeholder('Deskripsi gambar untuk aksesibilitas'),
                    ]),
            ]);
    }
}
