<?php

namespace SudoSuu\FilamentBlog\Resources\PostResource\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use SudoSuu\FilamentBlog\Events\BlogPublished;
use SudoSuu\FilamentBlog\Models\Post;
use SudoSuu\FilamentBlog\Resources\PostResource;
use Illuminate\Contracts\Support\Htmlable;

class ViewPost extends ViewRecord
{
  protected static string $resource = PostResource::class;


  protected static ?string $title = 'عرض المقال';

  public function getTitle(): string|Htmlable
  {
    $record = $this->getRecord();
    return 'عرض المقال: ' . $record->title;
  }

  protected function getHeaderActions(): array
  {
    return [
      Action::make('sendNotification')
        ->label('إرسال إشعار')
        ->requiresConfirmation()
        ->icon('heroicon-o-bell')
        ->action(function (Post $record) {
          event(new BlogPublished($record));
        })
        ->disabled(fn(Post $record) => $record->isNotPublished()),

      Action::make('preview')
        ->label('معاينة')
        ->requiresConfirmation()
        ->icon('heroicon-o-eye')
        ->url(fn(Post $record) => route('filamentblog.post.show', $record->slug), true)
      // ->disabled(fn(Post $record) => $record->isNotPublished()),
    ];
  }
}
