<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // DB::table('users')->insert([
        //     'department_id' => 1,   // Administração
        //     'name' => 'Administrador',
        //     'email' => 'admin@rhmangnt.com',
        //     'email_verified_at' => now(),
        //     'password' => bcrypt('Aa123456'),
        //     'role' => 'admin',
        //     'permissions' => '["admin"]',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
        User::factory()->count(50)->create();
    }
}
