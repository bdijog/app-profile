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
            Stat::make(__('Incoming Questions'), $pendingQuestions)
                ->description($pendingQuestions > 0 ? __('Waiting for admin response') : __('All questions resolved'))
                ->descriptionIcon($pendingQuestions > 0 ? Heroicon::OutlinedExclamationCircle : Heroicon::OutlinedCheckCircle)
                ->color($pendingQuestions > 0 ? 'warning' : 'success'),

            Stat::make(__('Answered Questions'), $answeredQuestions)
                ->description(__('Published to the public'))
                ->descriptionIcon(Heroicon::OutlinedChatBubbleLeftRight)
                ->color('success'),

            Stat::make(__('Published Articles'), $publishedPosts)
                ->description(Post::count().' '.__('total saved articles'))
                ->descriptionIcon(Heroicon::OutlinedDocumentText)
                ->color('primary'),

            Stat::make(__('Active Slides'), $activeSlides)
                ->description(__('Showing on the home page'))
                ->descriptionIcon(Heroicon::OutlinedPhoto)
                ->color('info'),
        ];
    }
}
