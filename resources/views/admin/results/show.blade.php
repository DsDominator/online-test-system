<x-app-layout>
<x-slot name="header"><h2 class="font-semibold text-xl">Chấm bài: {{ $user->name }} – {{ $exam->title }}</h2></x-slot>
<div class="py-6 max-w-5xl mx-auto">
@if(session('success'))
<div class="p-3 bg-green-100 border mb-4">{{ session('success') }}</div>
@endif
@foreach($answers as $ans)
<div class="mb-4 p-4 border rounded">
<div class="font-semibold mb-1">{!! nl2br(e($ans->question->question_text)) !!}</div>
<div class="mb-2">Trả lời: {!! nl2br(e($ans->answer_text)) !!}</div>
<div class="flex items-center gap-2">
<form method="POST" action="{{ route('admin.results.score', [$exam->id, $ans->id]) }}">
@csrf
<input type="number" name="score" step="0.01" min="0" max="100" value="{{ $ans->score }}" class="border p-1 rounded w-28">
<button class="btn btn-secondary">Lưu điểm</button>
</form>
</div>
</div>
@endforeach


<form method="POST" action="{{ route('admin.results.finalize', [$exam->id, $user->id]) }}" class="mt-6">
@csrf
<button class="btn btn-primary">Hoàn tất & Gửi email</button>
</form>
</div>
</x-app-layout>