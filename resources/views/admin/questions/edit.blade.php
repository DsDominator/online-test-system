<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Sửa câu hỏi – {{ $exam->title }}
        </h2>
    </x-slot>

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

        <form method="POST" action="{{ route('admin.questions.update', $question) }}">
            @csrf @method('PUT')
            <input type="hidden" name="page" value="{{ request('page', 1) }}">

            @include('admin.questions._form')

            <div class="flex items-center gap-3 mt-3">
                <button class="btn btn-primary">💾 Cập nhật </button>
                <a href="{{ route('admin.exams.questions.index', ['exam' => $exam->id, 'page' => request('page',1)]) }}"
                class="btn btn-secondary">← Quay lại</a>
            </div>
        </form>

    </div>
</x-app-layout>

