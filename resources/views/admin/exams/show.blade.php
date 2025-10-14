<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Exam: {{ $exam->title }}</h2></x-slot>
    <div class="max-w-3xl mx-auto p-6 space-y-4">
        <div>Thời lượng: <b>{{ $exam->duration }}</b> phút</div>
        <div>Tổng câu hỏi: <b>{{ $exam->total_questions }}</b></div>

        <div class="flex gap-2">
            <a class="btn btn-secondary" href="{{ route('admin.exams.edit', $exam) }}">Sửa</a>
            <form method="POST" action="{{ route('admin.exams.destroy', $exam) }}" onsubmit="return confirm('Xoá exam này?');">
                @csrf @method('DELETE')
                <button class="btn btn-danger">Xoá</button>
            </form>
            <a class="btn" href="{{ route('admin.exams.index') }}">Quay lại</a>
        </div>
    </div>
</x-app-layout>
