<?php

namespace SudoSuu\FilamentBlog\Resources\SettingResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use SudoSuu\FilamentBlog\Resources\SettingResource;

class CreateSetting extends CreateRecord
{
  protected static string $resource = SettingResource::class;

  //    protected function beforeCreate(): void
//    {
//        dd($this->data);
//    }
}
