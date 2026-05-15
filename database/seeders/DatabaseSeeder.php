<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Note;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@notehub.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Regular User
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@notehub.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create Categories for Admin
        $catWork = Category::create([
            'user_id' => $admin->id,
            'name' => 'Work',
            'color' => '#3b82f6',
        ]);
        
        $catPersonal = Category::create([
            'user_id' => $admin->id,
            'name' => 'Personal',
            'color' => '#10b981',
        ]);

        // Create Notes for Admin
        Note::create([
            'user_id' => $admin->id,
            'category_id' => $catWork->id,
            'title' => 'Project Architecture',
            'content' => 'We need to use Laravel 12, Livewire, and Tailwind for the upcoming SaaS project.',
        ]);

        Note::create([
            'user_id' => $admin->id,
            'category_id' => $catPersonal->id,
            'title' => 'Groceries',
            'content' => 'Milk, Eggs, Bread, and Coffee.',
        ]);
        
        // Create Categories for User
        $userCat = Category::create([
            'user_id' => $user->id,
            'name' => 'Study',
            'color' => '#8b5cf6',
        ]);

        Note::create([
            'user_id' => $user->id,
            'category_id' => $userCat->id,
            'title' => 'Math Exam Prep',
            'content' => 'Review chapters 4 and 5.',
        ]);
    }
}
