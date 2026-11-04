<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Questions\QuestionResource;
use App\Models\Question;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestQuestionsWidget extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Pertanyaan Masyarakat Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Question::query()->with('topic')->latest()->limit(5))
            ->columns([
                TextColumn::make('subject')
                    ->label('Subjek Pertanyaan')
                    ->limit(50),
                TextColumn::make('name')
                    ->label('Penanya'),
                TextColumn::make('topic.name')
                    ->label('Topik')
                    ->badge(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->since(),
            ])
            ->paginated(false)
            ->recordActions([
                Action::make('answer')
                    ->label('Tanggapi')
                    ->url(fn (Question $record): string => QuestionResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
