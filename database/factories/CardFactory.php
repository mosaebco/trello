<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Testing\Fakes\Fake;
use SebastianBergmann\Type\FalseType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    
    public function definition(): array
    {
        return [
            'title' => fake()->sentence,
        ];
    }
}
