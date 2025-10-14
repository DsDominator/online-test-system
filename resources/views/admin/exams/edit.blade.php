<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Sửa Exam #{{ $exam->id }}</h2></x-slot>
    <div class="max-w-3xl mx-auto p-6">
        <form method="POST" action="{{ route('admin.exams.update', $exam) }}">
            @method('PUT')
            @include('admin.exams._form', ['exam'=>$exam])
            <div class="flex gap-2">
                <button class="btn btn-primary">Cập nhật</button>
                <a class="btn" href="{{ route('admin.exams.index') }}">Quay lại</a>
            </div>
        </form>
    </div>
</x-app-layout>

