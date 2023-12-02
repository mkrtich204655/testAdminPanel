<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (config('categories') as $ite){
            Category::factory()->create([
                'name' => $ite
            ]);
        };
    }
}
