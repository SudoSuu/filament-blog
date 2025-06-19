<?php

namespace SudoSuu\FilamentBlog\Database\Factories;

use SudoSuu\FilamentBlog\Models\Comment;
use SudoSuu\FilamentBlog\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Comment::class;

  /**
   * Define the model's default state.
   */
  public function definition(): array
  {
    return [
      'user_id' => User::factory(),
      'comment' => $this->faker->word,
      'approved' => false,
    ];
  }
}
