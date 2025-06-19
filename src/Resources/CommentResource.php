<?php

namespace SudoSuu\FilamentBlog\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use SudoSuu\FilamentBlog\Models\Comment;
use SudoSuu\FilamentBlog\Tables\Columns\UserPhotoName;

class CommentResource extends Resource
{
  protected static ?string $model = Comment::class;

  protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

  protected static ?string $navigationGroup = 'Blog';

  protected static ?int $navigationSort = 5;

  public static function form(Form $form): Form
  {
    return $form
      ->schema(Comment::getForm());
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        UserPhotoName::make('user')
          ->label('User'),
        Tables\Columns\TextColumn::make('post.title')
          ->numeric()
          ->limit(20)
          ->sortable(),
        Tables\Columns\TextColumn::make('comment')
          ->searchable()
          ->limit(20),
        Tables\Columns\ToggleColumn::make('approved')
          ->beforeStateUpdated(function ($record, $state) {
            if ($state) {
              $record->approved_at = now();
            } else {
              $record->approved_at = null;
            }

            return $state;
          }),
        Tables\Columns\TextColumn::make('approved_at')
          ->sortable()
          ->placeholder('Not approved yet'),

        Tables\Columns\TextColumn::make('created_at')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('updated_at')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        Tables\Filters\SelectFilter::make('user')
          ->relationship('user', config('filamentblog.user.columns.name'))
          ->searchable()
          ->preload()
          ->multiple(),
      ])
      ->actions([
        ActionGroup::make([
          Tables\Actions\EditAction::make(),
          Tables\Actions\DeleteAction::make(),
          Tables\Actions\ViewAction::make(),
        ]),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      //
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => \SudoSuu\FilamentBlog\Resources\CommentResource\Pages\ListComments::route('/'),
      'create' => \SudoSuu\FilamentBlog\Resources\CommentResource\Pages\CreateComment::route('/create'),
      'edit' => \SudoSuu\FilamentBlog\Resources\CommentResource\Pages\EditComment::route('/{record}/edit'),
    ];
  }
}
