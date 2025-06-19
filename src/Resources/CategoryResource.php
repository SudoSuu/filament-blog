<?php

namespace SudoSuu\FilamentBlog\Resources;

use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use SudoSuu\FilamentBlog\Models\Category;
use SudoSuu\FilamentBlog\Resources\CategoryResource\RelationManagers\PostsRelationManager;

class CategoryResource extends Resource
{
  protected static ?string $model = Category::class;

  protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

  protected static ?string $navigationGroup = 'مدونة';

  protected static ?int $navigationSort = 1;

  protected static ?string $label = 'تصنيف';
  protected static ?string $pluralLabel = 'التصنيفات';

  public static function form(Form $form): Form
  {
    return $form
      ->schema(Category::getForm());
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('name')
          ->label('الاسم')
          ->searchable(),
        Tables\Columns\TextColumn::make('slug')
          ->label('الرابط'),
        Tables\Columns\TextColumn::make('posts_count')
          ->label('عدد المقالات')
          ->badge()
          ->counts('posts'),
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
      ->filters([
        // يمكن إضافة فلاتر هنا
      ])
      ->actions([
        Tables\Actions\EditAction::make()->label('تعديل'),
        Tables\Actions\ViewAction::make()->label('عرض'),
        Tables\Actions\DeleteAction::make()->label('حذف'),
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
      Section::make('معلومات التصنيف')
        ->schema([
          TextEntry::make('name')->label('الاسم'),
          TextEntry::make('slug')->label('الرابط'),
        ])->columns(2)
        ->icon('heroicon-o-square-3-stack-3d'),
    ]);
  }

  public static function getRelations(): array
  {
    return [
      PostsRelationManager::class,
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => \SudoSuu\FilamentBlog\Resources\CategoryResource\Pages\ListCategories::route('/'),
      'edit' => \SudoSuu\FilamentBlog\Resources\CategoryResource\Pages\EditCategory::route('/{record}/edit'),
      'view' => \SudoSuu\FilamentBlog\Resources\CategoryResource\Pages\ViewCategory::route('/{record}'),
    ];
  }
}
