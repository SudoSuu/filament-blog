<?php

namespace SudoSuu\FilamentBlog\Resources\PostResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use SudoSuu\FilamentBlog\Models\SeoDetail;

class SeoDetailRelationManager extends RelationManager
{
  protected static string $relationship = 'seoDetail';

  protected static ?string $recordTitleAttribute = 'title';
  protected static ?string $title = 'تفاصيل السيو';

  public function form(Form $form): Form
  {
    return $form
      ->schema(SeoDetail::getForm());
  }

  public function table(Table $table): Table
  {
    return $table
      ->recordTitleAttribute('title')
      ->columns([
        Tables\Columns\TextColumn::make('title')
          ->label('عنوان السيو'),

        Tables\Columns\TextColumn::make('description')
          ->label('الوصف'),

        Tables\Columns\TextColumn::make('keywords')
          ->label('الكلمات المفتاحية')
          ->badge(),
      ])
      ->filters([
        // فلاتر مستقبلية
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
