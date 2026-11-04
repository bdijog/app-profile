<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MenuItemType: string implements HasLabel
{
    case Page = 'page';
    case Post = 'post';
    case Category = 'category';
    case Custom = 'custom';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Page => __('Static Page'),
            self::Post => __('Article/Post'),
            self::Category => __('Category'),
            self::Custom => __('Custom URL'),
        };
    }
}
