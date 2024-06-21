<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['pending', 'completed']),
            'is_completed' => $this->faker->boolean,
            'start_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'expired_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'user_id' => User::factory(),
            'company_id' => Company::factory(),
        ];
    }
}
