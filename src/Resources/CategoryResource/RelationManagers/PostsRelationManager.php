<?php

namespace SudoSuu\FilamentBlog\Resources\CategoryResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use SudoSuu\FilamentBlog\Models\Post;
use Illuminate\Support\Str;

class PostsRelationManager extends RelationManager
{
  protected static string $relationship = 'posts';

  protected static ?string $recordTitleAttribute = 'title';
  protected static ?string $title = 'المقالات';

  public function isReadOnly(): bool
  {
    return false;
  }

  public function form(Form $form): Form
  {
    return $form->schema(Post::getForm());
  }

  public function table(Table $table): Table
  {
    return $table
      ->recordTitleAttribute('title')
      ->columns([
        Tables\Columns\TextColumn::make('title')
          ->label('العنوان')
          ->limit(40)
          ->description(fn(Post $record) => Str::limit($record->sub_title)),

        Tables\Columns\TextColumn::make('status')
          ->label('الحالة')
          ->badge()
          ->color(fn($state) => $state->getColor()),
      ])
      ->filters([
        // فلاتر يمكن إضافتها لاحقًا
      ])
      ->headerActions([
        Tables\Actions\CreateAction::make()->label('إضافة'),
      ])
      ->actions([
        Tables\Actions\EditAction::make()
          ->label('تعديل')
          ->slideOver(),

        Tables\Actions\DeleteAction::make()->label('حذف'),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
        ]),
      ]);
  }
}
