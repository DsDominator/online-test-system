<x-app-layout>
<x-slot name="header"><h2 class="font-semibold text-xl">Kết quả: {{ $exam->title }}</h2></x-slot>
<div class="py-6 max-w-5xl mx-auto">
@if(session('success'))
<div class="p-3 bg-green-100 border mb-4">{{ session('success') }}</div>
@endif
<div class="mb-4">Tổng điểm: <strong>{{ optional($result)->total_score ?? 'Đang chấm' }}</strong></div>
@foreach($answers as $ans)
<div class="mb-4 p-4 border rounded">
<div class="font-semibold mb-1">{!! nl2br(e($ans->question->question_text)) !!}</div>
<div class="mb-1">Trả lời: {!! nl2br(e($ans->answer_text)) !!}</div>
<div>Điểm: {{ $ans->score === null ? 'Chờ chấm' : $ans->score }}</div>
</div>
@endforeach
</div>
</x-app-layout>