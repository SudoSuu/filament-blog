<?php

namespace SudoSuu\FilamentBlog\Resources\CategoryResource\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use SudoSuu\FilamentBlog\Models\Category;
use SudoSuu\FilamentBlog\Resources\CategoryResource;

class ViewCategory extends ViewRecord
{
  protected static string $resource = CategoryResource::class;

  public function getHeaderActions(): array
  {
    return [
      EditAction::make()
        ->slideOver()
        ->form(Category::getForm()),
    ];
  }
}
