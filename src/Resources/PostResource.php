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

  protected static ?string $navigationGroup = 'مدونة';

  protected static ?string $recordTitleAttribute = 'title';

  protected static ?int $navigationSort = 3;

  protected static ?string $label = 'مقال';
  protected static ?string $pluralLabel = 'المقالات';

  protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

  public static function getNavigationBadge(): ?string
  {
    return strval(Post::count());
  }

  public static function form(Form $form): Form
  {
    return $form->schema(Post::getForm());
  }

  public static function table(Table $table): Table
  {
    return $table
      ->deferLoading()
      ->columns([
        Tables\Columns\TextColumn::make('title')
          ->label('العنوان')
          ->description(fn(Post $record) => Str::limit($record->sub_title, 40))
          ->searchable()
          ->limit(20),

        Tables\Columns\TextColumn::make('status')
          ->label('الحالة')
          ->badge()
          ->color(fn($state) => $state->getColor()),

        Tables\Columns\ImageColumn::make('cover_photo_path')
          ->label('صورة الغلاف'),

        UserPhotoName::make('user')
          ->label('الكاتب'),

        Tables\Columns\TextColumn::make('created_at')
          ->label('تاريخ الإنشاء')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),

        Tables\Columns\TextColumn::make('updated_at')
          ->label('تاريخ التعديل')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->defaultSort('id', 'desc')
      ->filters([
        Tables\Filters\SelectFilter::make('user')
          ->label('الكاتب')
          ->relationship('user', config('filamentblog.user.columns.name'))
          ->searchable()
          ->preload()
          ->multiple(),
      ])
      ->actions([
        Tables\Actions\ActionGroup::make([
          Tables\Actions\EditAction::make()->label('تعديل'),
          Tables\Actions\ViewAction::make()->label('عرض'),
        ]),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
        ]),
      ]);
  }

  public static function infolist(Infolist $infolist): Infolist
  {
    return $infolist->schema([
      Section::make('المقال')
        ->schema([
          Fieldset::make('عام')
            ->schema([
              TextEntry::make('title')->label('العنوان'),
              TextEntry::make('slug')->label('الرابط'),
              TextEntry::make('sub_title')->label('العنوان الفرعي'),
            ]),

          Fieldset::make('معلومات النشر')
            ->schema([
              TextEntry::make('status')
                ->label('الحالة')
                ->badge()
                ->color(fn($state) => $state->getColor()),

              TextEntry::make('published_at')
                ->label('تاريخ النشر')
                ->visible(fn(Post $record) => $record->status === PostStatus::PUBLISHED),

              TextEntry::make('scheduled_for')
                ->label('تاريخ المجدولة')
                ->visible(fn(Post $record) => $record->status === PostStatus::SCHEDULED),
            ]),

          Fieldset::make('الوصف')
            ->schema([
              TextEntry::make('body')
                ->label('المحتوى')
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
      // علاقات مستقبلية مثل التعليقات أو بيانات السيو
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
