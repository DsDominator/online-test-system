<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Exam, Question};

class QuestionController extends Controller
{
    public function index(Exam $exam)
    {
        $questions = $exam->questions()
            ->orderBy('order_no', 'asc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.questions.index', compact('exam', 'questions'));
    }

    public function create(Exam $exam)
    {
        return view('admin.questions.create', compact('exam'));
    }

    public function store(Request $request, Exam $exam)
    {
        if ($request->filled('options_text')) {
            $opts = preg_split("/(\r\n|\n|\r)/", trim($request->input('options_text')));
            $request->merge(['options' => array_values(array_filter($opts, fn($s)=>$s!==''))]);
        }

        $data = $request->validate([
            'type'           => 'required|in:multiple_choice,essay',
            'question_text'  => 'required|string',
            'options'        => 'nullable|array',
            'correct_answer' => 'nullable|string',
        ]);

        if ($data['type'] === 'multiple_choice') {
            $request->validate([
                'options'        => 'required|array|min:2',
                'options.*'      => 'required|string',
                'correct_answer' => 'required|string',
            ]);
        } else {
            $data['options'] = null;
            $data['correct_answer'] = null;
        }

        // ✅ Transaction để tránh trùng order_no khi tạo đồng thời
        DB::transaction(function () use ($exam, $data) {
            $maxOrder = $exam->questions()->lockForUpdate()->max('order_no') ?? 0;
            $payload = $data + ['order_no' => $maxOrder + 1];
            $exam->questions()->create($payload);
            $exam->update([
            'total_questions' => $exam->questions()->count()
            ]);
        });

        return redirect()->route('admin.exams.questions.index', [
            'exam' => $exam->id,
            'page' => $request->input('page', 1), // giữ trang nếu có
        ])->with('success', 'Thêm câu hỏi thành công!');
    }

    public function edit(Request $request, Question $question)
    {
        $question->load('exam');          // đảm bảo đã có quan hệ
        $exam = $question->exam;
        $page = $request->query('page', 1); // lấy ?page=..., mặc định 1

        return view('admin.questions.edit', compact('exam', 'question', 'page'));
    }


    public function update(Request $request, Question $question)
    {
        if ($request->filled('options_text')) {
            $opts = preg_split("/(\r\n|\n|\r)/", trim($request->input('options_text')));
            $request->merge(['options' => array_values(array_filter($opts, fn($s) => $s !== ''))]);
        }

        $data = $request->validate([
            'type'           => 'required|in:multiple_choice,essay',
            'question_text'  => 'required|string',
            'options'        => 'nullable|array',
            'correct_answer' => 'nullable|string',
        ]);

        if ($data['type'] === 'multiple_choice') {
            $request->validate([
                'options'        => 'required|array|min:2',
                'options.*'      => 'required|string',
                'correct_answer' => 'required|string',
            ]);
        } else {
            $data['options'] = null;
            $data['correct_answer'] = null;
        }

        $question->update($data);

        return redirect()->route('admin.exams.questions.index', $question->exam_id)
            ->with('success', 'Cập nhật câu hỏi thành công!');
    }


    public function destroy(Question $question)
    {
        $exam = $question->exam;
        $question->delete();
        $exam->update(['total_questions' => $exam->questions()->count()]);

        // (Tùy chọn) Dồn lại thứ tự 1..n
        $exam->questions()->orderBy('order_no')->get()->each(function ($q, $i) {
            if ($q->order_no !== $i + 1) {
                $q->update(['order_no' => $i + 1]);
            }
        });

        return redirect()->route('admin.exams.questions.index', [
            'exam' => $exam->id,
            'page' => request('page', 1),
        ])->with('success', 'Đã xóa câu hỏi.');
    }
}
