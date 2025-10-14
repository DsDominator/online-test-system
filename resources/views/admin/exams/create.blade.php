<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Tạo Exam</h2></x-slot>
    <div class="max-w-3xl mx-auto p-6">
        <form method="POST" action="{{ route('admin.exams.store') }}">
            @include('admin.exams._form')
            <div class="flex gap-2">
                <button class="btn btn-primary">Lưu</button>
                <a class="btn" href="{{ route('admin.exams.index') }}">Huỷ</a>
            </div>
        </form>
    </div>
</x-app-layout>
