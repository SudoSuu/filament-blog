<?php

namespace SudoSuu\FilamentBlog\Resources\CategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use SudoSuu\FilamentBlog\Resources\CategoryResource;

class EditCategory extends EditRecord
{
  protected static string $resource = CategoryResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\DeleteAction::make(),
    ];
  }
}
