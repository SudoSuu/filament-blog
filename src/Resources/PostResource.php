<?php

namespace SudoSuu\FilamentBlog\Resources;

use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use SudoSuu\FilamentBlog\Enums\PostStatus;
use SudoSuu\FilamentBlog\Models\Post;
use SudoSuu\FilamentBlog\Resources\PostResource\Pages\EditPost;
use SudoSuu\FilamentBlog\Resources\PostResource\Pages\ManaePostSeoDetail;
use SudoSuu\FilamentBlog\Resources\PostResource\Pages\ManagePostComments;
use SudoSuu\FilamentBlog\Resources\PostResource\Pages\ViewPost;
use SudoSuu\FilamentBlog\Resources\PostResource\Widgets\BlogPostPublishedChart;
use SudoSuu\FilamentBlog\Tables\Columns\UserPhotoName;
use Illuminate\Support\Str;

class PostResource extends Resource
{
  protected static ?string $model = Post::class;

  protected static ?string $navigationIcon = 'heroicon-o-document-minus';

  protected static ?string $navigationGroup = 'Blog';

  protected static ?string $recordTitleAttribute = 'title';

  protected static ?int $navigationSort = 3;

  protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

  public static function getNavigationBadge(): ?string
  {
    return strval(Post::count());
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema(Post::getForm());
  }

  public static function table(Table $table): Table
  {
    return $table
      ->deferLoading()
      ->columns([
        Tables\Columns\TextColumn::make('title')
          ->description(function (Post $record) {
            return Str::limit($record->sub_title, 40);
          })
          ->searchable()->limit(20),
        Tables\Columns\TextColumn::make('status')
          ->badge()
          ->color(function ($state) {
            return $state->getColor();
          }),
        Tables\Columns\ImageColumn::make('cover_photo_path')->label('Cover Photo'),

        UserPhotoName::make('user')
          ->label('Author'),

        Tables\Columns\TextColumn::make('created_at')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('updated_at')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])->defaultSort('id', 'desc')
      ->filters([
        Tables\Filters\SelectFilter::make('user')
          ->relationship('user', config('filamentblog.user.columns.name'))
          ->searchable()
          ->preload()
          ->multiple(),
      ])
      ->actions([
        Tables\Actions\ActionGroup::make([
          Tables\Actions\EditAction::make(),
          Tables\Actions\ViewAction::make(),
        ]),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function infolist(Infolist $infolist): Infolist
  {
    return $infolist->schema([
      Section::make('Post')
        ->schema([
          Fieldset::make('General')
            ->schema([
              TextEntry::make('title'),
              TextEntry::make('slug'),
              TextEntry::make('sub_title'),
            ]),
          Fieldset::make('Publish Information')
            ->schema([
              TextEntry::make('status')
                ->badge()->color(function ($state) {
                  return $state->getColor();
                }),
              TextEntry::make('published_at')->visible(function (Post $record) {
                return $record->status === PostStatus::PUBLISHED;
              }),

              TextEntry::make('scheduled_for')->visible(function (Post $record) {
                return $record->status === PostStatus::SCHEDULED;
              }),
            ]),
          Fieldset::make('Description')
            ->schema([
              TextEntry::make('body')
                ->html()
                ->columnSpanFull(),
            ]),
        ]),
    ]);
  }

  public static function getRecordSubNavigation(Page $page): array
  {
    return $page->generateNavigationItems([
      ViewPost::class,
      ManaePostSeoDetail::class,
      ManagePostComments::class,
      EditPost::class,
    ]);
  }

  public static function getRelations(): array
  {
    return [
      //            \SudoSuu\FilamentBlog\Resources\PostResource\RelationManagers\SeoDetailRelationManager::class,
      //            \SudoSuu\FilamentBlog\Resources\PostResource\RelationManagers\CommentsRelationManager::class,
    ];
  }

  public static function getWidgets(): array
  {
    return [
      BlogPostPublishedChart::class,
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => \SudoSuu\FilamentBlog\Resources\PostResource\Pages\ListPosts::route('/'),
      'create' => \SudoSuu\FilamentBlog\Resources\PostResource\Pages\CreatePost::route('/create'),
      'edit' => \SudoSuu\FilamentBlog\Resources\PostResource\Pages\EditPost::route('/{record}/edit'),
      'view' => \SudoSuu\FilamentBlog\Resources\PostResource\Pages\ViewPost::route('/{record}'),
      'comments' => \SudoSuu\FilamentBlog\Resources\PostResource\Pages\ManagePostComments::route('/{record}/comments'),
      'seoDetail' => \SudoSuu\FilamentBlog\Resources\PostResource\Pages\ManaePostSeoDetail::route('/{record}/seo-details'),
    ];
  }
}
