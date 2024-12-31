<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Str;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure at least one user exists
        $user = User::first() ?? User::factory()->create();

        // Seed 10 tasks for the user
        for ($i = 1; $i <= 10; $i++) {
            Task::create([
                'user_id' => $user->id,
                'title' => "Task Title $i",
                'description' => "This is the description for task $i.",
                'status' => ['Pending', 'In Progress', 'Completed'][array_rand(['Pending', 'In Progress', 'Completed'])],
                'due_date' => now()->addDays(rand(1, 30))->format('Y-m-d'),
            ]);
        }
    }
}
