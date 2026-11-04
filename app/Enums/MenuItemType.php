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
            self::Page => 'Halaman Statis',
            self::Post => 'Artikel/Post',
            self::Category => 'Kategori',
            self::Custom => 'URL Kustom',
        };
    }
}
