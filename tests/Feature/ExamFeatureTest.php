<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Exam;
use App\Models\Question;

class ExamFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function student_can_submit_and_mcq_is_auto_graded_and_results_saved()
    {
        // Arrange
        $user = User::factory()->create();
        $exam = Exam::factory()->create(['duration' => 45]);

        // Tạo 1 MCQ + 1 Essay => mỗi câu 100/2 = 50 điểm
        $mcq = Question::create([
            'exam_id'        => $exam->id,
            'type'           => 'multiple_choice',
            'question_text'  => '2+2=?',
            'options'        => ['1','2','3','4'],
            'correct_answer' => '4',
        ]);
        $essay = Question::create([
            'exam_id'        => $exam->id,
            'type'           => 'essay',
            'question_text'  => 'Giải thích PHP là gì?',
        ]);

        // Giả lập đã bắt đầu trong 1 phút (vẫn trong 45')
        $sessionKey = "exam_start_{$exam->id}_{$user->id}";
        $this->actingAs($user)
            ->withSession([$sessionKey => now()->subMinute()])
            ->post(route('student.exams.submit', $exam), [
                'answers' => [
                    $mcq->id   => '4',      // đúng
                    $essay->id => 'Lorem',  // essay
                ],
            ])
            ->assertRedirect(route('student.results.show', $exam->id));

        // Assert: Lưu đáp án
        $this->assertDatabaseHas('student_answers', [
            'user_id'     => $user->id,
            'exam_id'     => $exam->id,
            'question_id' => $mcq->id,
            'score'       => 50.00, // 100/2
        ]);
        $this->assertDatabaseHas('student_answers', [
            'user_id'     => $user->id,
            'exam_id'     => $exam->id,
            'question_id' => $essay->id,
            'score'       => null,  // essay chờ chấm
        ]);

        // Assert: Tổng điểm
        $this->assertDatabaseHas('exam_results', [
            'user_id'     => $user->id,
            'exam_id'     => $exam->id,
            'total_score' => 50.00,
        ]);
    }
}
