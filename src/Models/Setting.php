<?php

namespace SudoSuu\FilamentBlog\Models;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use SudoSuu\FilamentBlog\Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Descriptor\TextDescriptor;

class Setting extends Model
{
  use HasFactory;

  protected $fillable = [
    'title',
    'description',
    'logo',
    'favicon',
    'organization_name',
    'google_console_code',
    'google_analytic_code',
    'google_adsense_code',
    'quick_links',
  ];

  protected $casts = [
    'quick_links' => 'json',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
  ];

  protected function getLogoImageAttribute()
  {
    return asset('storage/' . $this->logo);
  }

  protected function getFavIconImageAttribute()
  {
    return asset('storage/' . $this->favicon);
  }

  protected static function newFactory()
  {
    return new SettingFactory();
  }

  public static function getForm(): array
  {
    return [
      Section::make('معلومات عامة')
        ->schema([
          TextInput::make('title')
            ->label('عنوان الموقع')
            ->maxLength(155)
            ->required(),

          TextInput::make('organization_name')
            ->label('اسم المؤسسة')
            ->required()
            ->maxLength(155)
            ->minLength(3),

          Textarea::make('description')
            ->label('الوصف')
            ->required()
            ->minLength(10)
            ->maxLength(1000)
            ->columnSpanFull(),

          FileUpload::make('logo')
            ->label('شعار الموقع')
            ->hint('أقصى ارتفاع 400 بكسل')
            ->directory('setting/logo')
            ->maxSize(1024 * 1024 * 2)
            ->rules('dimensions:max_height=400')
            ->nullable()
            ->columnSpanFull(),

          FileUpload::make('favicon')
            ->label('أيقونة الموقع (Favicon)')
            ->directory('setting/favicon')
            ->maxSize(50)
            ->nullable()
            ->columnSpanFull(),
        ])
        ->columns(2),

      Section::make('إعدادات SEO')
        ->description('ضع كود تحليلات Google وAdSense هنا. سيتم إضافته في وسم <head> لصفحات المقالات فقط.')
        ->schema([
          Textarea::make('google_console_code')
            ->label('كود Google Console')
            ->startsWith('<meta')
            ->placeholder('مثال: <meta name="google-site-verification" content="your_verification_code" />')
            ->nullable()
            ->columnSpanFull(),

          Textarea::make('google_analytic_code')
            ->label('كود Google Analytics')
            ->placeholder('مثال: <script async src="https://www.googletagmanager.com/gtag/js?id=YOUR_TRACKING_ID"></script>')
            ->startsWith('<script')
            ->endsWith('</script>')
            ->nullable()
            ->columnSpanFull(),

          // Textarea::make('google_adsense_code')
          //   ->label('كود Google AdSense')
          //   ->startsWith('<script')
          //   ->endsWith('</script>')
          //   ->nullable()
          //   ->columnSpanFull(),
        ])
        ->columns(2),

      Section::make('روابط سريعة')
        ->description('أضف روابطك السريعة هنا، ستظهر في تذييل الموقع.')
        ->schema([
          Repeater::make('quick_links')
            ->label('روابط')
            ->schema([
              TextInput::make('label')
                ->label('اسم الرابط')
                ->required()
                ->maxLength(155),

              TextInput::make('url')
                ->label('الرابط (URL)')
                ->helperText('يجب أن يبدأ الرابط بـ http:// أو https://')
                ->required()
                ->url()
                ->maxLength(255),
            ])
            ->columns(2),
        ])
        ->columnSpanFull(),
    ];
  }


  public function getTable()
  {
    return config('filamentblog.tables.prefix') . 'settings';
  }
}
