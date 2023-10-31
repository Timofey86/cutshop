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

    #[ArrayShape(['title' => "string", 'thumbnail' => "string", 'on_home_page' => "boolean", 'sorting' => "integer"])] public function definition(): array
    {
        return [
            'title' => $this->faker->company(),
            'thumbnail' => $this->faker->loremflick('brands', 'images/brands'),
            'on_home_page' => $this->faker->boolean(),
            'sorting' => $this->faker->numberBetween(1,999)
        ];
    }
}
