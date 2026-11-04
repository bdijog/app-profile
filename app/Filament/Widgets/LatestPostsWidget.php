<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestPostsWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Artikel & Berita Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Post::query()->with('category')->latest()->limit(5))
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Artikel')
                    ->limit(50),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('view_count')
                    ->label('Dilihat')
                    ->numeric(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->since(),
            ])
            ->paginated(false)
            ->recordActions([
                Action::make('edit')
                    ->label('Buka')
                    ->url(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
