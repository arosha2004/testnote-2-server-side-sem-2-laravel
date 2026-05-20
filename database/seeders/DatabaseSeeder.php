<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Device;
use App\Models\Note;
use App\Models\NoteVersion;
use App\Models\Reminder;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@notehub.com',
            'password' => Hash::make('password'),
            'user_role' => 'admin',
            'phone_number' => '0771234567',
        ]);

        $user = User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@notehub.com',
            'password' => Hash::make('password'),
            'user_role' => 'user',
        ]);

        Device::create([
            'user_id' => $admin->id,
            'device_type' => 'laptop',
            'os' => 'Windows 11',
            'last_accessed_time' => now(),
        ]);

        $catWork = Category::create(['category_name' => 'Work']);
        $catPersonal = Category::create(['category_name' => 'Personal']);
        $catStudy = Category::create(['category_name' => 'Study']);

        $noteArchitecture = Note::create([
            'user_id' => $admin->id,
            'title' => 'Project Architecture',
            'content' => 'We need to use Laravel 12, Livewire, and Tailwind for the upcoming SaaS project.',
            'is_pinned' => true,
        ]);

        $noteArchitecture->categories()->attach($catWork->id, [
            'description' => 'SaaS planning notes',
        ]);

        NoteVersion::create([
            'note_id' => $noteArchitecture->id,
            'version_no' => 1,
            'updated_content' => 'Initial draft of architecture notes.',
            'updated_date' => now()->subDay(),
        ]);

        Reminder::create([
            'note_id' => $noteArchitecture->id,
            'status' => 'pending',
            'notification_type' => 'email',
            'reminder_date_time' => now()->addDays(2),
            'repeat_type' => 'none',
        ]);

        $noteGroceries = Note::create([
            'user_id' => $admin->id,
            'title' => 'Groceries',
            'content' => 'Milk, Eggs, Bread, and Coffee.',
        ]);

        $noteGroceries->categories()->attach($catPersonal->id, [
            'description' => 'Weekly shopping list',
        ]);

        $noteExam = Note::create([
            'user_id' => $user->id,
            'title' => 'Math Exam Prep',
            'content' => 'Review chapters 4 and 5.',
        ]);

        $noteExam->categories()->attach($catStudy->id, [
            'description' => 'Semester 2 revision',
        ]);
    }
}
