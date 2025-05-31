<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'user_id' => User::factory(),
            'status' => PostStatus::DRAFT,
            'scheduled_at' => null,
            'platform_specific_data' => null,
        ];
    }

    public function published(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => PostStatus::PUBLISHED,
                'scheduled_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            ];
        });
    }

    public function scheduled(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => PostStatus::SCHEDULED,
                'scheduled_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            ];
        });
    }

    public function draft(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => PostStatus::DRAFT,
                'scheduled_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            ];
        });
    }
}
