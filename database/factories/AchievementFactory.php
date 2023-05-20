<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Achievement>
 */
class AchievementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'one' => '0000',
            'two' => 0,
            'three' => 0,
            'four' => 0,
            'five' => 0,
            'six' => 0,
            'seven' => 0,
            'eight' => 0,
            'nine' => 0,
            'ten' => 0,
            'eleven' => 0,
            'twelve' => 0,
            'thirteen' => 0,
            'fourteen' => 0,
        ];
    }
}
