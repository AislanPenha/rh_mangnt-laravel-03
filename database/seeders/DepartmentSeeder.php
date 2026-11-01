<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'Administração',
            'Recursos Humanos',
            'Financeiro',
            'Tecnologia da Informação',
            'Marketing',
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate([
                'name' => $department,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // DB::table('departments')->insert([
        //     'name' => 'Administração',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
    }
}
