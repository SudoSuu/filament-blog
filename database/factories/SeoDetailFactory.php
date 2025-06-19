<?php

namespace SudoSuu\FilamentBlog\Database\Factories;

use SudoSuu\FilamentBlog\Models\Post;
use SudoSuu\FilamentBlog\Models\SeoDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeoDetailFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = SeoDetail::class;

  /**
   * Define the model's default state.
   */
  public function definition(): array
  {
    $keywords = $this->faker->randomElements(SeoDetail::KEYWORDS, 3);

    return [
      'post_id' => Post::factory(),
      'title' => $this->faker->sentence(4),
      'keywords' => $keywords,
      'description' => $this->faker->sentence(1),
    ];
  }
}
