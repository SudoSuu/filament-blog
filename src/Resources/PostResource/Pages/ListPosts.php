<?php

namespace SudoSuu\FilamentBlog\Resources\PostResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use SudoSuu\FilamentBlog\Resources\PostResource;
use SudoSuu\FilamentBlog\Resources\PostResource\Widgets\BlogPostPublishedChart;

class ListPosts extends ListRecords
{
  protected static string $resource = PostResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make()->label('إضافة مقال'),
    ];
  }

  protected function getHeaderWidgets(): array
  {
    return [
      BlogPostPublishedChart::class,
    ];
  }

  public function getTabs(): array
  {
    return [
      'all' => Tab::make('الكل'),

      'published' => Tab::make('منشور')
        ->modifyQueryUsing(fn($query) => $query->published())
        ->icon('heroicon-o-check-badge'),

      'pending' => Tab::make('مسودة')
        ->modifyQueryUsing(fn($query) => $query->pending())
        ->icon('heroicon-o-clock'),

      'scheduled' => Tab::make('مجدوَل')
        ->modifyQueryUsing(fn($query) => $query->scheduled())
        ->icon('heroicon-o-calendar-days'),
    ];
  }
}
