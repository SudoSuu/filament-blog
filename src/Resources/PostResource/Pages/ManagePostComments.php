<?php

namespace SudoSuu\FilamentBlog\Resources\PostResource\Pages;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use SudoSuu\FilamentBlog\Resources\PostResource;
use SudoSuu\FilamentBlog\Tables\Columns\UserPhotoName;
use Illuminate\Contracts\Support\Htmlable;

class ManagePostComments extends ManageRelatedRecords
{
  protected static string $resource = PostResource::class;

  protected static string $relationship = 'comments';

  protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

  public function getTitle(): string|Htmlable
  {
    $recordTitle = $this->getRecordTitle();
    $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;

    return 'إدارة التعليقات';
  }

  public function getBreadcrumb(): string
  {
    return 'التعليقات';
  }

  public static function getNavigationLabel(): string
  {
    return 'إدارة التعليقات';
  }

  public function form(Form $form): Form
  {
    return $form
      ->schema([
        Select::make('user_id')
          ->label('المستخدم')
          ->relationship('user', config('filamentblog.user.columns.name'))
          ->required(),

        Textarea::make('comment')
          ->label('التعليق')
          ->required()
          ->maxLength(65535)
          ->columnSpanFull(),

        Toggle::make('approved')
          ->label('تمت الموافقة'),
      ])
      ->columns(1);
  }

  public function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('comment')
          ->label('التعليق')
          ->searchable(),

        UserPhotoName::make('user')
          ->label('تم التعليق بواسطة'),

        Tables\Columns\ToggleColumn::make('approved')
          ->label('تمت الموافقة')
          ->beforeStateUpdated(function ($record, $state) {
            $record->approved_at = $state ? now() : null;
            return $state;
          }),

        Tables\Columns\TextColumn::make('approved_at')
          ->label('تاريخ الموافقة')
          ->placeholder('لم تتم الموافقة')
          ->sortable(),

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
        Tables\Filters\SelectFilter::make('user')
          ->label('المستخدم')
          ->relationship('user', config('filamentblog.user.columns.name'))
          ->searchable()
          ->preload()
          ->multiple(),
      ])
      ->headerActions([
        Tables\Actions\CreateAction::make()->label('إضافة'),
      ])
      ->actions([
        Tables\Actions\ActionGroup::make([
          Tables\Actions\EditAction::make()->label('تعديل'),
          Tables\Actions\ViewAction::make()->label('عرض'),
          Tables\Actions\DeleteAction::make()->label('حذف'),
        ]),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
        ]),
      ]);
  }

  public function infolist(Infolist $infolist): Infolist
  {
    return $infolist->schema([
      Section::make('التعليق')
        ->schema([
          TextEntry::make('user.name')->label('تم التعليق بواسطة'),
          TextEntry::make('comment')->label('النص'),
          TextEntry::make('created_at')->label('تاريخ الإنشاء'),
          TextEntry::make('approved_at')->label('تاريخ الموافقة')->placeholder('لم تتم الموافقة'),
        ])
        ->icon('heroicon-o-chat-bubble-left-ellipsis'),
    ]);
  }
}
