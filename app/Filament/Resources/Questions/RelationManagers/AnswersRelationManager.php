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
use Illuminate\Support\Facades\Notification;

class AnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';

    protected static ?string $title = 'Jawaban Resmi';

    protected static ?string $modelLabel = 'Jawaban';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                RichEditor::make('body')
                    ->label('Isi Tanggapan / Jawaban')
                    ->required()
                    ->columnSpanFull(),
                Grid::make(3)->schema([
                    Select::make('status')
                        ->label('Status Jawaban')
                        ->options(AnswerStatus::class)
                        ->default(AnswerStatus::Published->value)
                        ->required(),
                    Toggle::make('is_published')
                        ->label('Publikasikan Jawaban')
                        ->default(true)
                        ->helperText('Jika aktif, jawaban akan tampil ke publik/Flutter.'),
                    DateTimePicker::make('answered_at')
                        ->label('Waktu Menjawab')
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
                    ->label('Dijawab Oleh')
                    ->default(fn () => auth()->user()?->name)
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                IconColumn::make('is_published')
                    ->label('Publik')
                    ->boolean(),
                TextColumn::make('answered_at')
                    ->label('Waktu Menjawab')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tulis Jawaban')
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
