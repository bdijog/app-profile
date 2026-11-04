<?php

namespace App\Filament\Resources\Questions\Schemas;

use App\Enums\QuestionStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Penanya')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->required()
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->label('No. Telepon / WA')
                                ->tel()
                                ->maxLength(255),
                        ]),
                    ]),

                Section::make('Isi Pertanyaan')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('topic_id')
                                ->label('Topik Pertanyaan')
                                ->relationship('topic', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('status')
                                ->label('Status Pertanyaan')
                                ->options(QuestionStatus::class)
                                ->default(QuestionStatus::Pending->value)
                                ->required(),
                        ]),
                        TextInput::make('subject')
                            ->label('Subjek / Judul Pertanyaan')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('body')
                            ->label('Isi Pertanyaan Lengkap')
                            ->rows(5)
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
