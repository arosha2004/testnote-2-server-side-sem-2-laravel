<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Creating a test user first.');
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
            $users = collect([$user]);
        }

        $defaultCategories = [
            ['name' => 'Study', 'color' => '#4f46e5'],     // Indigo
            ['name' => 'Important', 'color' => '#ef4444'], // Red
            ['name' => 'Work', 'color' => '#059669'],      // Green
            ['name' => 'Ideas', 'color' => '#f59e0b'],     // Amber
            ['name' => 'Personal', 'color' => '#ec4899'],  // Pink
            ['name' => 'To-Do', 'color' => '#8b5cf6'],     // Violet
        ];

        foreach ($users as $user) {
            foreach ($defaultCategories as $cat) {
                Category::firstOrCreate(
                    ['user_id' => $user->id, 'name' => $cat['name']],
                    ['color' => $cat['color']]
                );
            }
        }

        $this->command->info('Categories seeded successfully for all users.');
    }
}
