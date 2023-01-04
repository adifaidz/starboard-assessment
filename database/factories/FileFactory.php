<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Pharaonic\Laravel\Readable\Readable;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
      return [
        'name' => fake()->name(),
        'hashed_name' => fake()->name(),
        'size' => ReadableSize(fake()->numberBetween(0, 400000), false),
        'labels' => [],
      ];
    }
}
