<?php

namespace SudoSuu\FilamentBlog\Resources\PostResource\Pages;

use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use SudoSuu\FilamentBlog\Resources\PostResource;
use Illuminate\Contracts\Support\Htmlable;

class ManaePostSeoDetail extends ManageRelatedRecords
{
  protected static string $resource = PostResource::class;

  protected static string $relationship = 'seoDetail';

  protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

  public function getTitle(): string|Htmlable
  {
    $recordTitle = $this->getRecordTitle();
    $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;

    return 'إدارة بيانات SEO';
  }

  public static function getNavigationLabel(): string
  {
    return 'إدارة بيانات SEO';
  }

  protected function canCreate(): bool
  {
    return !$this->getRelationship()->count();
  }

  public function form(Form $form): Form
  {
    return $form
      ->schema([
        TextInput::make('title')
          ->label('عنوان السيو')
          ->required()
          ->maxLength(255)
          ->columnSpanFull(),

        TagsInput::make('keywords')
          ->label('الكلمات المفتاحية')
          ->columnSpanFull(),

        Textarea::make('description')
          ->label('الوصف')
          ->required()
          ->maxLength(65535)
          ->columnSpanFull(),
      ]);
  }

  public function table(Table $table): Table
  {
    return $table
      ->recordTitleAttribute('title')
      ->columns([
        Tables\Columns\TextColumn::make('title')
          ->label('عنوان السيو')
          ->limit(20),

        Tables\Columns\TextColumn::make('description')
          ->label('الوصف')
          ->limit(40),

        Tables\Columns\TextColumn::make('keywords')
          ->label('الكلمات المفتاحية')
          ->badge(),

        Tables\Columns\TextColumn::make('created_at')
          ->label('تاريخ الإنشاء')
          ->dateTime()
          ->toggleable(isToggledHiddenByDefault: true),

        Tables\Columns\TextColumn::make('updated_at')
          ->label('تاريخ التعديل')
          ->dateTime()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        // يمكن إضافة فلاتر لاحقاً
      ])
      ->headerActions([
        Tables\Actions\CreateAction::make()->label('إضافة'),
      ])
      ->actions([
        Tables\Actions\EditAction::make()->label('تعديل'),
        Tables\Actions\ViewAction::make()->label('عرض'),
      ])
      ->paginated(false);
  }
}
