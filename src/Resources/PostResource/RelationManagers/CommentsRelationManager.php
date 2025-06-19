<?php

namespace SudoSuu\FilamentBlog\Resources\PostResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CommentsRelationManager extends RelationManager
{
  protected static string $relationship = 'comments';


  protected static ?string $recordTitleAttribute = 'comment';
  protected static ?string $title = 'التعليقات';

  public function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('comment')
          ->label('التعليق')
          ->required()
          ->maxLength(255),
      ]);
  }

  public function table(Table $table): Table
  {
    return $table
      ->recordTitleAttribute('comment')
      ->columns([
        Tables\Columns\TextColumn::make('comment')
          ->label('التعليق')
          ->limit(20),

        Tables\Columns\TextColumn::make('user.name')
          ->label('المستخدم'),
      ])
      ->filters([
        // فلاتر يمكن إضافتها لاحقًا
      ])
      ->headerActions([
        Tables\Actions\CreateAction::make()->label('إضافة'),
      ])
      ->actions([
        Tables\Actions\EditAction::make()->label('تعديل'),
        Tables\Actions\DeleteAction::make()->label('حذف'),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
        ]),
      ]);
  }
}
