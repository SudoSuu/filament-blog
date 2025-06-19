<?php

namespace SudoSuu\FilamentBlog\Resources\PostResource\Pages;

use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use SudoSuu\FilamentBlog\Events\BlogPublished;
use SudoSuu\FilamentBlog\Jobs\PostScheduleJob;
use SudoSuu\FilamentBlog\Resources\PostResource;
use SudoSuu\FilamentBlog\Resources\SeoDetailResource;

class CreatePost extends CreateRecord
{
  protected static string $resource = PostResource::class;

  //    protected function mutateFormDataBeforeCreate(array $data): array
  //    {
  //        dd($data);
  //    }

  protected function afterCreate()
  {
    if ($this->record->isScheduled()) {

      $now = Carbon::now();
      $scheduledFor = Carbon::parse($this->record->scheduled_for);
      PostScheduleJob::dispatch($this->record)
        ->delay($now->diffInSeconds($scheduledFor));
    }
    if ($this->record->isStatusPublished()) {
      $this->record->published_at = date('Y-m-d H:i:s');
      $this->record->save();
      event(new BlogPublished($this->record));
    }
  }

  protected function getRedirectUrl(): string
  {
    return SeoDetailResource::getUrl('create', ['post_id' => $this->record->id]);
  }
}
