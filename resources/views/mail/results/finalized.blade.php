@component('mail::message')
# Xin chào {{ $student->name }},
Bạn đã hoàn thành bài kiểm tra **{{ $exam->title }}**.


**Tổng điểm: {{ $result->total_score }} / 100**


Cảm ơn bạn đã tham gia.
@endcomponent