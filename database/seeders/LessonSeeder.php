<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lesson 1
        \App\Models\Lesson::create([
            'title' => 'Rukun Islam (The 5 Pillars)',
            'description' => 'Learn about the foundation of every Muslim\'s life.',
            'content' => 'The 5 Pillars are: 1. Shahada (Faith), 2. Salah (Prayer), 3. Zakat (Charity), 4. Sawm (Fasting), 5. Hajj (Pilgrimage). These are the framework of Muslim life.',
            'points' => 50
        ]);

        // Lesson 2
        \App\Models\Lesson::create([
            'title' => 'Adab to Parents',
            'description' => 'How should we treat our mother and father?',
            'content' => 'In Islam, treating parents with kindness is one of the most important deeds. Allah says in the Quran: "And your Lord has decreed that you not worship except Him, and to parents, good treatment."',
            'points' => 30
        ]);

        // Lesson 3
        \App\Models\Lesson::create([
            'title' => 'Cleanliness (Taharah)',
            'description' => 'Purity is half of Faith.',
            'content' => 'Taharah means being clean. Before we pray, we must perform Wudhu. This washes away dirt and minor sins. We wash our face, arms, head, and feet.',
            'points' => 20
        ]);
    }
}
