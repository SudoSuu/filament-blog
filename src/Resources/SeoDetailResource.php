<?php

namespace SudoSuu\FilamentBlog\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use SudoSuu\FilamentBlog\Models\SeoDetail;

class SeoDetailResource extends Resource
{
  protected static ?string $model = SeoDetail::class;

  protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

  protected static ?string $navigationGroup = 'مدونة';

  protected static ?int $navigationSort = 4;

  protected static ?string $label = 'تفاصيل السيو';
  protected static ?string $pluralLabel = 'تفاصيل السيو';

  public static function form(Form $form): Form
  {
    return $form
      ->schema(SeoDetail::getForm());
  }

  public static function table(Table $table): Table
  {
    return $table
      ->striped()
      ->columns([
        Tables\Columns\TextColumn::make('post.title')
          ->label('عنوان المقال')
          ->limit(20),

        Tables\Columns\TextColumn::make('title')
          ->label('عنوان السيو')
          ->limit(20)
          ->searchable(),

        Tables\Columns\TextColumn::make('keywords')
          ->label('الكلمات المفتاحية')
          ->badge()
          ->searchable(),

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
        // يمكن إضافة فلاتر هنا
      ])
      ->actions([
        Tables\Actions\EditAction::make()->label('تعديل'),
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
      'index' => \SudoSuu\FilamentBlog\Resources\SeoDetailResource\Pages\ListSeoDetails::route('/'),
      'create' => \SudoSuu\FilamentBlog\Resources\SeoDetailResource\Pages\CreateSeoDetail::route('/create'),
      'edit' => \SudoSuu\FilamentBlog\Resources\SeoDetailResource\Pages\EditSeoDetail::route('/{record}/edit'),
    ];
  }
}
