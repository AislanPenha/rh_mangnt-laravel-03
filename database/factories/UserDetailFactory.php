<?php

namespace Database\Factories;

use App\Models\UserDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserDetail>
 */
class UserDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'address' => fake()->streetAddress(),
            'zip_code' => fake()->postcode(),
            'city' => fake()->city(),
            'phone' => fake()->randomElement([
                                        '11', '21', '31', '41', '51',
                                        '61', '71', '81', '85', '98', '99'
                                    ]) . fake()->numerify('9########'),
            'salary' => fake()->randomFloat(2, 1500, 15000),
            'admission_date' => fake()->dateTimeBetween('-10 years', 'now'),
        ];
    }
}
