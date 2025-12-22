<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Optional: Create 10 random users
        // User::factory(10)->create();

        User::factory()->create([
            // FIX: Use 'full_name' instead of 'username' or 'name'
            'full_name' => 'Test User',
            
            // FIX: You MUST provide a matric_id since your migration marks it as unique and required
            'matric_id' => 'admin123', 
            
            'email' => 'test@example.com',
            'role' => 'admin', // Optional: simpler for testing
        ]);
    }
}
