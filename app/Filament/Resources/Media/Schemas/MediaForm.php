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
                Section::make(__('Upload File'))
                    ->schema([
                        FileUpload::make('file_path')
                            ->label(__('Choose File / Image'))
                            ->directory('uploads')
                            ->visibility('public')
                            ->required()
                            ->storeFileNamesIn('file_name'),
                        TextInput::make('alt_text')
                            ->label(__('Alternative Text (Alt Text)'))
                            ->placeholder(__('Image description for accessibility')),
                    ]),
            ]);
    }
}
