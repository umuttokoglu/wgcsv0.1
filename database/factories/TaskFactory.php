<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'provider_id' => $this->faker->numberBetween(1, 2),
            'name' => $this->faker->sentence(3),
            'duration' => $this->faker->numberBetween(1, 10),
            'difficulty' => $this->faker->numberBetween(1, 5),
        ];
    }
}
