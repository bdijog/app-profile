<?php

namespace App\Filament\Resources\Questions\RelationManagers;

use App\Enums\AnswerStatus;
use App\Enums\QuestionStatus;
use App\Models\QuestionAnswer;
use App\Notifications\QuestionAnsweredNotification;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

class AnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Official Answers');
    }

    public static function getModelLabel(): string
    {
        return __('Answer');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Answers');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                RichEditor::make('body')
                    ->label(__('Response / Answer Content'))
                    ->required()
                    ->columnSpanFull(),
                Grid::make(3)->schema([
                    Select::make('status')
                        ->label(__('Answer Status'))
                        ->options(AnswerStatus::class)
                        ->default(AnswerStatus::Published->value)
                        ->required(),
                    Toggle::make('is_published')
                        ->label(__('Publish Answer'))
                        ->default(true)
                        ->helperText(__('If enabled, the answer will be visible to public/Flutter.')),
                    DateTimePicker::make('answered_at')
                        ->label(__('Answered At'))
                        ->default(now()),
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('Answered By'))
                    ->default(fn () => auth()->user()?->name)
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),
                IconColumn::make('is_published')
                    ->label(__('Public'))
                    ->boolean(),
                TextColumn::make('answered_at')
                    ->label(__('Answered At'))
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('Write Answer'))
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        $data['answered_at'] ??= now();

                        return $data;
                    })
                    ->after(function (QuestionAnswer $record) {
                        $question = $this->getOwnerRecord();
                        $question->update(['status' => QuestionStatus::Answered->value]);

                        if (! empty($question->email) && ($record->is_published || $record->status === AnswerStatus::Published)) {
                            try {
                                Notification::route('mail', $question->email)
                                    ->notify(new QuestionAnsweredNotification($question, $record));
                            } catch (\Throwable $e) {
                                // Non-blocking if mail server is not configured in local
                            }
                        }
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
