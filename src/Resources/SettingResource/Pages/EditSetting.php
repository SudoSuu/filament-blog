<?php

namespace SudoSuu\FilamentBlog\Resources\SettingResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use SudoSuu\FilamentBlog\Resources\SettingResource;

class EditSetting extends EditRecord
{
  protected static string $resource = SettingResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\DeleteAction::make(),
    ];
  }
}
