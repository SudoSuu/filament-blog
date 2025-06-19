<?php

namespace SudoSuu\FilamentBlog\Http\Controllers;

use SudoSuu\FilamentBlog\Facades\SEOMeta;
use SudoSuu\FilamentBlog\Models\NewsLetter;
use SudoSuu\FilamentBlog\Models\Post;
use SudoSuu\FilamentBlog\Models\ShareSnippet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
  public function index(Request $request)
  {
    SEOMeta::setTitle('المدونة | ' . config('app.name'));

    $posts = Post::query()->with(['categories', 'user', 'tags'])
      ->published()
      ->paginate(10);

    return view('filament-blog::blogs.index', [
      'posts' => $posts,
    ]);
  }

  public function allPosts()
  {
    SEOMeta::setTitle('كل المقالات | ' . config('app.name'));

    $posts = Post::query()->with(['categories', 'user'])
      ->published()
      ->paginate(20);

    return view('filament-blog::blogs.all-post', [
      'posts' => $posts,
    ]);
  }

  public function search(Request $request)
  {
    $query = $request->get('query');
    SEOMeta::setTitle('نتائج البحث عن: ' . $query);

    $request->validate([
      'query' => 'required',
    ]);

    $searchedPosts = Post::query()
      ->with(['categories', 'user'])
      ->published()
      ->whereAny(['title', 'sub_title'], 'like', '%' . $query . '%')
      ->paginate(10)
      ->withQueryString();

    return view('filament-blog::blogs.search', [
      'posts' => $searchedPosts,
      'searchMessage' => 'نتائج البحث عن: ' . $query,
    ]);
  }

  public function show(Post $post)
  {
    SEOMeta::setTitle($post->seoDetail?->title ?? $post->title);
    SEOMeta::setDescription($post->seoDetail?->description);
    SEOMeta::setKeywords($post->seoDetail->keywords ?? []);

    $shareButton = ShareSnippet::query()->active()->first();

    $post->load([
      'user',
      'categories',
      'tags',
      'comments' => fn($query) => $query->approved(),
      'comments.user',
    ]);

    return view('filament-blog::blogs.show', [
      'post' => $post,
      'shareButton' => $shareButton,
    ]);
  }

  public function subscribe(Request $request)
  {
    $request->validate([
      'email' => [
        'required',
        'email',
        Rule::unique(NewsLetter::class, 'email'),
      ],
    ], [
      'email.unique' => 'لقد قمت بالاشتراك بالفعل.',
    ]);

    NewsLetter::create([
      'email' => $request->email,
    ]);

    return back()->with('success', 'تم الاشتراك بنجاح في النشرة البريدية.');
  }
}
