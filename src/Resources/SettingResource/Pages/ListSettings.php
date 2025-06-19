<?php

namespace SudoSuu\FilamentBlog\Resources\SettingResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use SudoSuu\FilamentBlog\Resources\SettingResource;

class ListSettings extends ListRecords
{
  protected static string $resource = SettingResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make(),
    ];
  }
}
