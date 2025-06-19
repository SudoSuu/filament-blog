<?php

use SudoSuu\FilamentBlog\Models\Category;
use SudoSuu\FilamentBlog\Models\Post;



it('has posts', function () {
  // Arrange
  $category = Category::factory()
    ->hasAttached(Post::factory()->count(3))
    ->create();

  // Act & Assert
  expect($category->posts)
    ->toHaveCount(3)
    ->each
    ->toBeInstanceOf(Post::class);
});
