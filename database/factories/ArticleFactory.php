<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'     => 1,
            'title'       => $this->faker->sentence(5),
            'description' => $this->faker->text(200),
            'status'      => 1,
            'created_at'  => Carbon::now(),
        ];
    }
}
