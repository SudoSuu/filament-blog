<?php

namespace SudoSuu\FilamentBlog\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use SudoSuu\FilamentBlog\Models\Tag;

class TagResource extends Resource
{
  protected static ?string $model = Tag::class;

  protected static ?string $navigationIcon = 'heroicon-o-tag';

  protected static ?string $navigationGroup = 'مدونة';

  protected static ?int $navigationSort = 2;

  protected static ?string $label = 'وسم';
  protected static ?string $pluralLabel = 'الوسوم';

  public static function form(Form $form): Form
  {
    return $form->schema(Tag::getForm());
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
        // فلاتر يمكن إضافتها لاحقاً
      ])
      ->actions([
        Tables\Actions\EditAction::make()->label('تعديل'),
        Tables\Actions\DeleteAction::make()->label('حذف'),
        Tables\Actions\ViewAction::make()->label('عرض'),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
        ]),
      ]);
  }

  public static function getRelations(): array
  {
    return [];
  }

  public static function getPages(): array
  {
    return [
      'index' => \SudoSuu\FilamentBlog\Resources\TagResource\Pages\ListTags::route('/'),
      'edit' => \SudoSuu\FilamentBlog\Resources\TagResource\Pages\EditTag::route('/{record}/edit'),
    ];
  }
}
