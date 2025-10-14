<x-app-layout>
    <x-slot name="header"><h2>Thêm câu hỏi – {{ $exam->title }}</h2></x-slot>

    <div class="max-w-3xl mx-auto py-6">
        @if ($errors->any())
            <div class="p-3 bg-red-100 border mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.exams.questions.store', $exam) }}">
            @csrf
            @include('admin.questions._form')
            <button class="btn btn-primary mt-2">Lưu</button>
            <a href="{{ route('admin.exams.questions.index', $exam) }}" class="btn">Hủy</a>
        </form>
    </div>
</x-app-layout>
