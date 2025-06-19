<?php

namespace SudoSuu\FilamentBlog\Resources\NewsletterResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use SudoSuu\FilamentBlog\Resources\NewsletterResource;

class ListNewsletters extends ListRecords
{
  protected static string $resource = NewsletterResource::class;

  protected static ?string $title = 'Newsletters Subscriber';

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make(),
    ];
  }
}
