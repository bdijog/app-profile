<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Questions\QuestionResource;
use App\Models\Question;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class LatestQuestionsWidget extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function getTableHeading(): string|Htmlable|null
    {
        return __('Latest Public Inquiries');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('Latest Public Inquiries'))
            ->query(fn (): Builder => Question::query()->with('topic')->latest()->limit(5))
            ->columns([
                TextColumn::make('subject')
                    ->label(__('Question Subject'))
                    ->limit(50),
                TextColumn::make('name')
                    ->label(__('Inquirer')),
                TextColumn::make('topic.name')
                    ->label(__('Topic'))
                    ->badge(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),
                TextColumn::make('created_at')
                    ->label(__('Time'))
                    ->since(),
            ])
            ->paginated(false)
            ->recordActions([
                Action::make('answer')
                    ->label(__('Respond'))
                    ->url(fn (Question $record): string => QuestionResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
