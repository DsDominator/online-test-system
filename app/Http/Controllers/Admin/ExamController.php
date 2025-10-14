<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Exam, Question};


class ExamController extends Controller
{
    // Danh sách exam + tìm kiếm
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));
        $exams = Exam::when($q, fn($query) => $query->where('title', 'like', "%{$q}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.exams.index', compact('exams', 'q'));
    }

    public function create()
    {
        return view('admin.exams.create');
    }

    // Tạo exam xong chuyển sang trang quản lý câu hỏi của exam đó
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'duration'        => 'nullable|integer|min:1',
        ]);

        $exam = Exam::create($data); // <-- LƯU LẠI VÀO $exam

        return redirect()->route('admin.exams.questions.index', $exam)
            ->with('success', 'Tạo exam thành công. Hãy thêm câu hỏi.');
    }

    // Xem chi tiết exam + danh sách câu hỏi
    public function show(Exam $exam)
    {
        $questions = $exam->questions()->latest()->paginate(10);
        return view('admin.exams.show', compact('exam', 'questions'));
    }

    // Form sửa exam (có thể kèm block câu hỏi)
    public function edit(Exam $exam)
    {
        $questions = $exam->questions()->latest()->paginate(10);
        return view('admin.exams.edit', compact('exam', 'questions'));
    }

    public function update(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'duration'        => 'nullable|integer|min:1',
        ]);

        $exam->update($data);

        return redirect()->route('admin.exams.index', $exam)->with('success', 'Cập nhật Exam thành công!');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('admin.exams.index')->with('success','Đã xoá Exam!');
    }
    public function start(Exam $exam)
    {
        $user = auth()->user();
        $sessionKey = "exam_start_{$exam->id}_{$user->id}";

        if (!session()->has($sessionKey)) {
            session([$sessionKey => now()]);
        }

        $startTime = \Carbon\Carbon::parse(session($sessionKey));
        $endsAt = $startTime->copy()->addMinutes($exam->duration ?? 45);

        $questions = $exam->questions()->inRandomOrder()->get();

        return view('student.exams.take', compact('exam', 'questions', 'startTime', 'endsAt'));
    }

}
