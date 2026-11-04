<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class LatestPostsWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function getTableHeading(): string|Htmlable|null
    {
        return __('Latest Articles & News');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('Latest Articles & News'))
            ->query(fn (): Builder => Post::query()->with('category')->latest()->limit(5))
            ->columns([
                TextColumn::make('title')
                    ->label(__('Article Title'))
                    ->limit(50),
                TextColumn::make('category.name')
                    ->label(__('Category'))
                    ->badge(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),
                TextColumn::make('view_count')
                    ->label(__('Views'))
                    ->numeric(),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->since(),
            ])
            ->paginated(false)
            ->recordActions([
                Action::make('edit')
                    ->label(__('Open'))
                    ->url(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
