<?php

namespace SudoSuu\FilamentBlog\Http\Controllers;

use SudoSuu\FilamentBlog\Models\Category;
use SudoSuu\FilamentBlog\Models\Tag;

class TagController extends Controller
{
  public function posts(Tag $tag)
  {
    $posts = $tag->load(['posts.user'])
      ->posts()
      ->published()
      ->paginate(25);

    return view('filament-blog::blogs.tag-post', [
      'posts' => $posts,
      'tag' => $tag,
    ]);
  }
}
