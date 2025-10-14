<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Exam,ExamResult,StudentAnswer,User};
use App\Mail\ResultFinalizedMail;
use Illuminate\Support\Facades\Mail;
class ResultController extends Controller
{
    public function index(Request $request){
        $query = ExamResult::with(['user','exam'])->latest();
        if($request->filled('exam_id')) $query->where('exam_id',$request->exam_id);
        if($request->filled('user_id')) $query->where('user_id',$request->user_id);
        $results = $query->paginate(20)->withQueryString();
        return view('admin.results.index', compact('results'));
    }


    public function show(Exam $exam, User $user){
        $answers = StudentAnswer::with('question')
        ->where('user_id',$user->id)->where('exam_id',$exam->id)->get();
        $result = ExamResult::where('user_id',$user->id)->where('exam_id',$exam->id)->firstOrFail();
        return view('admin.results.show', compact('exam','user','answers','result'));
    }


    // Admin updates essay score for a single answer
    public function scoreAnswer(Request $request, Exam $exam, StudentAnswer $answer){
        $request->validate(['score' => 'required|numeric|min:0|max:100']);
        $answer->update(['score'=>$request->score]);
        // Recalc total
        $total = StudentAnswer::where('user_id',$answer->user_id)
        ->where('exam_id',$exam->id)->sum('score');
        ExamResult::updateOrCreate(
        ['user_id'=>$answer->user_id,'exam_id'=>$exam->id],
        ['total_score'=>$total]
        );
        return back()->with('success','Đã cập nhật điểm.');
    }


    // Finalize and notify student (after manual essay grading)
    public function finalizeAndNotify(Exam $exam, User $user){
        $result = ExamResult::where('user_id',$user->id)->where('exam_id',$exam->id)->firstOrFail();
        Mail::to($user->email)->send(new ResultFinalizedMail($user, $exam, $result));
        return back()->with('success','Đã gửi email thông báo!');
    }
}
