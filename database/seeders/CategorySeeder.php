<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Artificial Intelligence',
            'Machine Learning',
            'Data Science',
            'Cybersecurity',
            'Blockchain',
            'Cloud Computing',
            'Internet of Things',
            'Software Development',
            'Mobile Development',
            'Web Development'
        ];

        foreach ($categories as $category) {
            Category::create(['title' => $category]);
        }
    }
}
