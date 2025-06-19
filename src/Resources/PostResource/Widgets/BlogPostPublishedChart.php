<?php

namespace SudoSuu\FilamentBlog\Resources\PostResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use SudoSuu\FilamentBlog\Models\Post;

class BlogPostPublishedChart extends BaseWidget
{
  protected function getStats(): array
  {
    return [
      BaseWidget\Stat::make('المقالات المنشورة', Post::published()->count()),
      BaseWidget\Stat::make('المقالات المجدولة', Post::scheduled()->count()),
      BaseWidget\Stat::make('المقالات مسودة ', Post::pending()->count()),
    ];
  }
}
