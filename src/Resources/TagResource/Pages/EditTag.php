<?php

namespace SudoSuu\FilamentBlog\Resources\TagResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use SudoSuu\FilamentBlog\Resources\TagResource;

class EditTag extends EditRecord
{
  protected static string $resource = TagResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\DeleteAction::make(),
    ];
  }
}
