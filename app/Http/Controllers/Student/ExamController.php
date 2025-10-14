<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Exam,Question,StudentAnswer,ExamResult};
use App\Services\GradingService;
use Illuminate\Support\Carbon;

class ExamController extends Controller
{
    public function list(){
        return view('student.exams.index', [
        'exams' => Exam::latest()->paginate(10)
        ]);
    }


        // Student\ExamController@start
    public function start(Exam $exam)
    {
        $userId = auth()->id();
        $key = "exam_start_{$exam->id}_{$userId}";

        if (!session()->has($key)) {
            session([$key => now()]);
        }

        $startTime = \Carbon\Carbon::parse(session($key));
        $endsAt = $startTime->copy()->addMinutes($exam->duration ?? 45);

        $questions = $exam->questions()->inRandomOrder()->get();
        return view('student.exams.take', compact('exam','questions','endsAt'));
    }



    public function submit(Request $request, Exam $exam, GradingService $grader){
        $data = $request->validate([
        'answers' => 'required|array', // [question_id => answer_text]
        ]);
        $user = auth()->user();
        $now = now();


        $totalScore = $grader->gradeAndPersist($user->id, $exam, $data['answers'], $now);


        // Upsert exam result
        $result = ExamResult::updateOrCreate(
        ['user_id'=>$user->id,'exam_id'=>$exam->id],
        ['total_score'=>$totalScore,'submitted_at'=>$now]
        );


        return redirect()->route('student.results.show', [$exam->id])
        ->with('success', 'Đã nộp bài. Tổng điểm tạm tính: '.$totalScore);
    }


    public function showResult(Exam $exam){
        $user = auth()->user();
        $answers = StudentAnswer::with('question')
        ->where('user_id',$user->id)->where('exam_id',$exam->id)->get();
        $result = ExamResult::where('user_id',$user->id)->where('exam_id',$exam->id)->first();
        return view('student.results.show', compact('exam','answers','result'));
    }
}
