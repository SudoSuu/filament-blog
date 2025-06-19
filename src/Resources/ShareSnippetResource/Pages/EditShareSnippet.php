<?php

namespace SudoSuu\FilamentBlog\Resources\ShareSnippetResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use SudoSuu\FilamentBlog\Resources\ShareSnippetResource;

class EditShareSnippet extends EditRecord
{
  protected static string $resource = ShareSnippetResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\DeleteAction::make(),
    ];
  }
}
