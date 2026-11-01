<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nome = fake('pt_BR')->firstName();
        $nomeMeio = fake('pt_BR')->firstName();
        $sobrenome = fake('pt_BR')->lastName();

        $email = strtolower(
            Str::slug("$nome.$sobrenome", '.') . fake()->unique()->numberBetween(100, 999)
        ) . '@example.com';

        $role = fake()->randomElement(['admin', 'rh', 'colaborator']);
        $permissions = ['create', 'read', 'update', 'delete'];
        if($role !== 'admin'){
            shuffle($permissions);
            $quantidade = fake()->numberBetween(1, count($permissions));
            $permissions = array_slice($permissions, 0, $quantidade);
        }
        return [
            'department_id' => fake()->numberBetween(1, 5),
            'name' => "$nome $nomeMeio $sobrenome",
            'email' => $email,
            'email_verified_at' => now(),
            'password' => bcrypt('Aa123456'),
            'role' => $role,
            'permissions' => json_encode($permissions),
        ];
    }
}
