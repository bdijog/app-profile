<?php

namespace App\Filament\Widgets;

use App\Enums\PostStatus;
use App\Enums\QuestionStatus;
use App\Models\Post;
use App\Models\Question;
use App\Models\Slide;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $pendingQuestions = Question::where('status', QuestionStatus::Pending->value)->count();
        $answeredQuestions = Question::where('status', QuestionStatus::Answered->value)->count();
        $publishedPosts = Post::where('status', PostStatus::Published->value)->count();
        $activeSlides = Slide::active()->count();

        return [
            Stat::make('Pertanyaan Masuk', $pendingQuestions)
                ->description($pendingQuestions > 0 ? 'Menunggu tanggapan admin' : 'Semua pertanyaan teratasi')
                ->descriptionIcon($pendingQuestions > 0 ? Heroicon::OutlinedExclamationCircle : Heroicon::OutlinedCheckCircle)
                ->color($pendingQuestions > 0 ? 'warning' : 'success'),

            Stat::make('Pertanyaan Terjawab', $answeredQuestions)
                ->description('Telah dipublikasikan ke warga')
                ->descriptionIcon(Heroicon::OutlinedChatBubbleLeftRight)
                ->color('success'),

            Stat::make('Artikel Terbit', $publishedPosts)
                ->description(Post::count().' total artikel tersimpan')
                ->descriptionIcon(Heroicon::OutlinedDocumentText)
                ->color('primary'),

            Stat::make('Slide Aktif', $activeSlides)
                ->description('Tayang di halaman utama')
                ->descriptionIcon(Heroicon::OutlinedPhoto)
                ->color('info'),
        ];
    }
}
