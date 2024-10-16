<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Developer;

class DeveloperFactory extends Factory
{
    protected $model = Developer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->userName,
            'efficiency' => $this->faker->numberBetween(1, 5),
            'weekly_hours' => 45,
        ];
    }
}
