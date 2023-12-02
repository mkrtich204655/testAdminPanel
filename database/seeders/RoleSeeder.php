<?php

namespace Database\Seeders;


use App\Enums\Roles as Enum;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Enum::cases() as $case){
            Role::factory()->create([
                'name' => $case->value,
                'guard_name' => 'web'
            ]);
        };
    }
}
