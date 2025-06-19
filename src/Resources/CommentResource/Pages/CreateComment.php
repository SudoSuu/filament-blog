<?php

namespace SudoSuu\FilamentBlog\Resources\CommentResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use SudoSuu\FilamentBlog\Resources\CommentResource;

class CreateComment extends CreateRecord
{
  protected static string $resource = CommentResource::class;
}
