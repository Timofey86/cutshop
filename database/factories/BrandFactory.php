<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends Factory<Brand>
 */
class BrandFactory extends Factory
{

    #[ArrayShape(['title' => "string", 'thumbnail' => "string"])] public function definition(): array
    {
        return [
            'title' => $this->faker->company(),
            //todo 3rd lesson
            'thumbnail' => ''
        ];
    }
}
