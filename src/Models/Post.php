<?php

namespace SudoSuu\FilamentBlog\Models;

use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use FilamentTiptapEditor\TiptapEditor;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use SudoSuu\FilamentBlog\Enums\PostStatus;
use Filament\Forms\Components\ToggleButtons;
use FilamentTiptapEditor\Enums\TiptapOutput;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SudoSuu\FilamentBlog\Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
  use HasFactory;

  protected $fillable = [
    'title',
    'slug',
    'sub_title',
    'body',
    'status',
    'published_at',
    'scheduled_for',
    'cover_photo_path',
    'photo_alt_text',
    'user_id',
  ];

  protected $dates = [
    'scheduled_for',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'published_at' => 'datetime',
    'scheduled_for' => 'datetime',
    'status' => PostStatus::class,
    'user_id' => 'integer',
  ];

  protected static function newFactory()
  {
    return new PostFactory();
  }

  public function categories()
  {
    return $this->belongsToMany(Category::class, config('filamentblog.tables.prefix') . 'category_' . config('filamentblog.tables.prefix') . 'post');
  }

  public function comments(): hasmany
  {
    return $this->hasMany(Comment::class);
  }

  public function tags(): BelongsToMany
  {
    return $this->belongsToMany(Tag::class, config('filamentblog.tables.prefix') . 'post_' . config('filamentblog.tables.prefix') . 'tag');
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(config('filamentblog.user.model'), config('filamentblog.user.foreign_key'));
  }

  public function seoDetail()
  {
    return $this->hasOne(SeoDetail::class);
  }

  public function isNotPublished()
  {
    return !$this->isStatusPublished();
  }

  public function scopePublished(Builder $query)
  {
    return $query->where('status', PostStatus::PUBLISHED)->latest('published_at');
  }

  public function scopeScheduled(Builder $query)
  {
    return $query->where('status', PostStatus::SCHEDULED)->latest('scheduled_for');
  }

  public function scopePending(Builder $query)
  {
    return $query->where('status', PostStatus::PENDING)->latest('created_at');
  }

  public function formattedPublishedDate()
  {
    return $this->published_at?->format('d M Y');
  }

  public function isScheduled()
  {
    return $this->status === PostStatus::SCHEDULED;
  }

  public function isStatusPublished()
  {
    return $this->status === PostStatus::PUBLISHED;
  }

  public function relatedPosts($take = 3)
  {
    return $this->whereHas('categories', function ($query) {
      $query->whereIn(config('filamentblog.tables.prefix') . 'categories.id', $this->categories->pluck('id'))
        ->whereNotIn(config('filamentblog.tables.prefix') . 'posts.id', [$this->id]);
    })->published()->with('user')->take($take)->get();
  }

  protected function getFeaturePhotoAttribute()
  {
    return asset('storage/' . $this->cover_photo_path);
  }

  public static function getForm()
  {
    return [
      Section::make('تفاصيل المقال')
        ->schema([
          Fieldset::make('العناوين')
            ->schema([
              Select::make('category_id')
                ->label('التصنيفات')
                ->multiple()
                ->preload()
                ->createOptionForm(Category::getForm())
                ->searchable()
                ->relationship('categories', 'name')
                ->columnSpanFull(),

              TextInput::make('title')
                ->label('عنوان المقال')
                ->live(true)
                ->afterStateUpdated(fn(Set $set, ?string $state) => $set(
                  'slug',
                  Str::slug($state, '-', 'ar')
                ))
                ->required()
                ->unique(config('filamentblog.tables.prefix') . 'posts', 'title', null, 'id')
                ->maxLength(255),

              TextInput::make('slug')
                ->label('الرابط (Slug)')
                ->maxLength(255),

              Textarea::make('sub_title')
                ->label('العنوان الفرعي')
                ->maxLength(255)
                ->columnSpanFull(),

              Select::make('tag_id')
                ->label('الوسوم')
                ->multiple()
                ->preload()
                ->createOptionForm(Tag::getForm())
                ->searchable()
                ->relationship('tags', 'name')
                ->columnSpanFull(),
            ]),

          TiptapEditor::make('body')

            ->label('المحتوى')
            ->profile('default')
            ->disableFloatingMenus()
            ->extraInputAttributes(['style' => 'max-height: 30rem; min-height: 24rem'])
            ->required()
            ->columnSpanFull(),

          Fieldset::make('صورة الغلاف')
            ->schema([
              FileUpload::make('cover_photo_path')
                ->label('صورة الغلاف')
                ->directory('/blog-feature-images')
                ->hint('تُستخدم هذه الصورة كصورة رئيسية للمقال. الحجم الموصى به: 1200 × 628')
                ->image()
                ->preserveFilenames()
                ->imageEditor()
                ->maxSize(1024 * 5)
                ->rules('dimensions:max_width=1920,max_height=1004')
                ->required(),

              TextInput::make('photo_alt_text')
                ->label('وصف الصورة البديل')
                ->required(),
            ])
            ->columns(1),

          Fieldset::make('الحالة')
            ->schema([
              ToggleButtons::make('status')
                ->label('الحالة')
                ->live()
                ->inline()
                ->options(PostStatus::class)
                ->required(),

              DateTimePicker::make('scheduled_for')
                ->label('تاريخ النشر المجدوَل')
                ->visible(fn($get) => $get('status') === PostStatus::SCHEDULED->value)
                ->required(fn($get) => $get('status') === PostStatus::SCHEDULED->value)
                ->minDate(now()->addMinutes(5))
                ->native(false),
            ]),

          Select::make(config('filamentblog.user.foreign_key'))
            ->label('الكاتب')
            ->relationship('user', config('filamentblog.user.columns.name'))
            ->nullable(false)
            ->default(auth()->id()),
        ]),
    ];
  }


  public function getTable()
  {
    return config('filamentblog.tables.prefix') . 'posts';
  }
}
