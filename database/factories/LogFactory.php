<?php

namespace Database\Factories;

use App\Models\Log;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Log>
 */
class LogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Log::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'level' => $this->faker->randomElement(['error', 'warning', 'info', 'debug']),
            'message' => $this->faker->sentence(),
            'error_message' => $this->faker->optional()->sentence(),
            'context' => $this->faker->optional()->randomElements(['key' => 'value'], 1),
            'file' => $this->faker->optional()->filePath(),
            'line' => $this->faker->optional()->numberBetween(1, 1000),
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'user_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the log is an error.
     */
    public function error(): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => 'error',
            'error_message' => $this->faker->sentence(),
        ]);
    }

    /**
     * Indicate that the log is a warning.
     */
    public function warning(): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => 'warning',
        ]);
    }

    /**
     * Indicate that the log is info.
     */
    public function info(): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => 'info',
        ]);
    }

    /**
     * Indicate that the log is debug.
     */
    public function debug(): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => 'debug',
        ]);
    }
}
