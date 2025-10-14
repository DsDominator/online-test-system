<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl">Câu hỏi – {{ $exam->title }}</h2>
            <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary">
                    ← Quay lại
            </a>
            <a href="{{ route('admin.exams.questions.create', $exam) }}" class="btn btn-primary">+ Thêm câu hỏi</a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-6">
        @if(session('success'))
            <div class="p-3 bg-green-100 border mb-4">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="p-3 mb-4 bg-red-100 border border-red-300 text-red-800 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <table class="w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">STT</th>
                    <th class="p-2 border">Nội dung</th>
                    <th class="p-2 border">Loại</th>
                    <th class="p-2 border">Đáp án đúng</th>
                    <th class="p-2 border w-48"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $q)
                    <tr>
                        <td class="p-2 border">{{ $q->order_no }}</td>
                        <td class="p-2 border">{{ \Illuminate\Support\Str::limit($q->question_text, 80) }}</td>
                        <td class="p-2 border">{{ $q->type }}</td>
                        <td class="p-2 border">{{ $q->correct_answer }}</td>
                        <td class="p-2 border text-right">
                            <a href="{{ route('admin.questions.edit', $q) }}" class="btn btn-secondary">Sửa</a>
                            <form action="{{ route('admin.questions.destroy', $q) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger" onclick="return confirm('Xóa câu hỏi này?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td class="p-3 border" colspan="5">Chưa có câu hỏi.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">{{ $questions->links() }}</div>
    </div>
</x-app-layout>
