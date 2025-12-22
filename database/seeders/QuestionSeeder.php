<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Questions for Lesson 1: Rukun Islam
        \App\Models\Question::create([
            'lesson_id' => 1, // Links to "Rukun Islam"
            'question_text' => 'How many Pillars of Islam are there?',
            'option_a' => '3',
            'option_b' => '5',
            'option_c' => '6',
            'option_d' => '1',
            'correct_option' => 'b'
        ]);

        \App\Models\Question::create([
            'lesson_id' => 1,
            'question_text' => 'What is the first pillar of Islam?',
            'option_a' => 'Zakat',
            'option_b' => 'Hajj',
            'option_c' => 'Shahada',
            'option_d' => 'Salah',
            'correct_option' => 'c'
        ]);

        // Questions for Lesson 2: Adab to Parents
        \App\Models\Question::create([
            'lesson_id' => 2, // Links to "Adab to Parents"
            'question_text' => 'What is the key to Paradise mentioned in Hadith regarding parents?',
            'option_a' => 'Under the feet of your mother',
            'option_b' => 'In the sky',
            'option_c' => 'In the mosque',
            'option_d' => 'In the ocean',
            'correct_option' => 'a'
        ]);
    }
}
