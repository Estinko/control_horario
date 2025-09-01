<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use PDO;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeRecord>
 */
class TimeRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = Carbon::parse(fake()->dateTimeThisYear());
        $start = preg_replace("/\.\d+Z/", 'Z', $date->toIso8601ZuluString());
        $end = preg_replace("/\.\d+Z/", 'Z', $date->copy()->addHours(8)->toIso8601ZuluString());

        return [
            'check_in' => $start,
            'check_out' => $end
        ];
    }
}
