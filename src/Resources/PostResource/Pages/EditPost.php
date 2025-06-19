<?php

namespace SudoSuu\FilamentBlog\Resources\PostResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use SudoSuu\FilamentBlog\Enums\PostStatus;
use SudoSuu\FilamentBlog\Resources\PostResource;

class EditPost extends EditRecord
{
  protected static string $resource = PostResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\DeleteAction::make(),
    ];
  }

  protected function beforeSave()
  {
    if ($this->data['status'] === PostStatus::PUBLISHED->value) {
      $this->record->published_at = $this->record->published_at ?? date('Y-m-d H:i:s');
    }
  }
}
