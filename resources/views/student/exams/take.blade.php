<x-app-layout>
<x-slot name="header">
<div class="flex items-center justify-between">
<h2 class="font-semibold text-xl">{{ $exam->title }}</h2>
<div id="timer" class="text-red-600 font-bold"></div>
</div>
</x-slot>


<div class="py-6 max-w-5xl mx-auto">
<form method="POST" action="{{ route('student.exams.submit', $exam) }}" id="examForm">
@csrf
@foreach($questions as $i => $q)
<div class="mb-6 p-4 border rounded">
<div class="font-semibold mb-2">Câu {{ $i+1 }}:</div>
<div class="mb-3">{!! nl2br(e($q->question_text)) !!}</div>


@if($q->type==='multiple_choice')
@foreach($q->options ?? [] as $opt)
<label class="block mb-1">
<input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" class="mr-2">
{{ $opt }}
</label>
@endforeach
@else
<textarea name="answers[{{ $q->id }}]" rows="4" class="w-full border rounded p-2"></textarea>
@endif
</div>
@endforeach


<button type="submit" class="btn btn-primary">Nộp bài</button>
</form>
</div>


<script>
    // endsAt do server tính, luôn đáng tin: KHÔNG reset được
    const serverEndsAt = Date.parse("{{ $endsAt->toIso8601String() }}");

    const storageKey = 'exam_ends_at_{{ $exam->id }}_{{ auth()->id() }}';
    const form  = document.getElementById('examForm');
    const timer = document.getElementById('timer');

    // Lấy endsAt từ localStorage (nếu từng lưu), dùng giá trị sớm hơn để an toàn
    let cached = parseInt(localStorage.getItem(storageKey) || '0', 10);
    let endsAt = Number.isFinite(cached) && cached > 0 ? Math.min(serverEndsAt, cached) : serverEndsAt;

    // Lưu lại (nếu chưa có hoặc lớn hơn serverEndsAt)
    if (!cached || cached > serverEndsAt) {
        localStorage.setItem(storageKey, String(serverEndsAt));
        endsAt = serverEndsAt;
    }

    // Dọn interval cũ nếu có (khi quay lại từ bfcache)
    if (window.__examTimerInterval) {
        clearInterval(window.__examTimerInterval);
    }

    function render() {
        const remaining = endsAt - Date.now();
        if (remaining <= 0) {
            timer.textContent = 'Còn lại: 00:00';
            // submit một lần duy nhất
            if (!window.__examAutoSubmitted) {
                window.__examAutoSubmitted = true;
                form.submit();
            }
            return;
        }
        const m = Math.floor(remaining / 60000);
        const s = Math.floor((remaining % 60000) / 1000);
        timer.textContent = `Còn lại: ${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
    }

    function startTimer() {
        render();
        window.__examTimerInterval = setInterval(render, 1000);
    }

    // Khởi động lần đầu
    startTimer();

    // Re-init khi quay lại từ lịch sử (bfcache) hoặc đổi tab/quay lại tab
    window.addEventListener('pageshow', (e) => {
        // persisted = true khi trang lấy từ bfcache
        if (e.persisted) {
            if (window.__examTimerInterval) clearInterval(window.__examTimerInterval);
            startTimer();
        } else {
            // Một số trình duyệt vẫn cần refresh render
            render();
        }
    });
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') render();
    });

    // Khi submit thành công, xóa key để lần thi sau không dính
    form.addEventListener('submit', () => {
        try { localStorage.removeItem(storageKey); } catch {}
        if (window.__examTimerInterval) clearInterval(window.__examTimerInterval);
    });
</script>


</x-app-layout>