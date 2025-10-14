<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl">Danh sách bài thi</h2>
</x-slot>


<div class="py-6 max-w-5xl mx-auto">
@foreach($exams as $exam)
<div class="p-4 border rounded mb-3 flex justify-between">
<div>
<div class="font-bold">{{ $exam->title }}</div>
<div>Thời lượng: {{ $exam->duration }} phút</div>
<div>Tổng câu hỏi: {{ $exam->total_questions }}</div>
</div>
<a class="btn btn-primary" href="{{ route('student.exams.start',$exam) }}">Bắt đầu</a>
</div>
@endforeach
{{ $exams->links() }}
</div>
</x-app-layout>