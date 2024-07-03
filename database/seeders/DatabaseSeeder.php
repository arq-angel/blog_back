<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'username' => 'obstinate2058',
            'first_name' => 'Obstinate',
            'last_name' => 'Jarvis',
            'email' => 'obstinate2058@gmail.com',
            'password' => bcrypt('BPWis9TsCTzWe8Y'),
            'email_verified_at' => now(),
            'is_active' => 1,
            'role' => 0,
        ]);

        $this->call([
            // CategorySeeder::class,
            // PostSeeder::class,
            // CommentSeeder::class,
        ]);
    }
}
