<?php

namespace SudoSuu\FilamentBlog\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use SudoSuu\FilamentBlog\Models\NewsLetter;

class NewsletterResource extends Resource
{
  protected static ?string $model = NewsLetter::class;

  protected static ?string $navigationIcon = 'heroicon-o-newspaper';

  protected static ?string $navigationGroup = 'مدونة';

  protected static ?int $navigationSort = 6;

  protected static ?string $label = 'نشرة بريدية';
  protected static ?string $pluralLabel = 'النشرات البريدية';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('email')
          ->label('البريد الإلكتروني')
          ->email()
          ->required()
          ->unique(ignoreRecord: true)
          ->maxLength(100),

        Forms\Components\Toggle::make('subscribed')
          ->label('مشترك؟')
          ->default(true)
          ->required()
          ->columnSpanFull(),
      ])
      ->columns(2);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('email')
          ->label('البريد الإلكتروني')
          ->searchable(),

        Tables\Columns\ToggleColumn::make('subscribed')
          ->label('مشترك؟'),

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
      'index' => \SudoSuu\FilamentBlog\Resources\NewsletterResource\Pages\ListNewsletters::route('/'),
      'create' => \SudoSuu\FilamentBlog\Resources\NewsletterResource\Pages\CreateNewsletter::route('/create'),
      'edit' => \SudoSuu\FilamentBlog\Resources\NewsletterResource\Pages\EditNewsletter::route('/{record}/edit'),
    ];
  }
}
