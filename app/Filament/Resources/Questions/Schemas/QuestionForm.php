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
                Section::make(__('Inquirer Information'))
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')
                                ->label(__('Full Name'))
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label(__('Email'))
                                ->email()
                                ->required()
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->label(__('Phone Number / WA'))
                                ->tel()
                                ->maxLength(255),
                        ]),
                    ]),

                Section::make(__('Question Details'))
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('topic_id')
                                ->label(__('Inquiry Topic'))
                                ->relationship('topic', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('status')
                                ->label(__('Inquiry Status'))
                                ->options(QuestionStatus::class)
                                ->default(QuestionStatus::Pending->value)
                                ->required(),
                        ]),
                        TextInput::make('subject')
                            ->label(__('Subject / Question Title'))
                            ->required()
                            ->maxLength(255),
                        Textarea::make('body')
                            ->label(__('Full Question Body'))
                            ->rows(5)
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
