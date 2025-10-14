<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exam = \App\Models\Exam::create(['title'=>'Demo Exam','duration'=>45,'total_questions'=>25]);
        // 20 MCQ
        for($i=1;$i<=20;$i++){
        \App\Models\Question::create([
        'exam_id'=>$exam->id,
        'type'=>'multiple_choice',
        'question_text'=>"MCQ #$i: 2+2=?",
        'options'=>['1','2','3','4'],
        'correct_answer'=>'4'
        ]);
        }
        // 5 essay
        for($i=1;$i<=5;$i++){
        \App\Models\Question::create([
        'exam_id'=>$exam->id,
        'type'=>'essay',
        'question_text'=>"Essay #$i: Trình bày quan điểm về ...",
        ]);
        }
        // admin user
        $admin = \App\Models\User::factory()->create([
        'name'=>'Admin', 'email'=>'admin@example.com', 'is_admin'=>true
        ]);
    }
}
