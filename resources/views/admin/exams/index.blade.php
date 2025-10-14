<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Quản lý Exam</h2></x-slot>

    <div class="max-w-5xl mx-auto p-6">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 border">{{ session('success') }}</div>
        @endif

        <div class="flex justify-between mb-4">
            <form class="flex gap-2">
                <input type="text" name="q" value="{{ $q }}" placeholder="Tìm theo tiêu đề..." class="border p-2 rounded">
                <button class="btn btn-secondary">Tìm</button>
            </form>
            <a href="{{ route('admin.exams.create') }}" class="btn btn-primary">+ Tạo Exam</a>
        </div>

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">ID</th>
                    <th class="p-2 border">Tiêu đề</th>
                    <th class="p-2 border">Thời lượng (phút)</th>
                    <th class="p-2 border">Tổng câu hỏi</th>
                    <th class="p-2 border w-48">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($exams as $exam)
                    <tr>
                        <td class="p-2 border">{{ $exam->id }}</td>
                        <td class="p-2 border">{{ $exam->title }}</td>
                        <td class="p-2 border">{{ $exam->duration }}</td>
                        <td class="p-2 border">{{ $exam->total_questions }}</td>
                        <td class="p-2 border">
                            <div class="flex gap-2">
                                <a class="btn"
                                href="{{ route('admin.exams.questions.index', $exam) }}">
                                    📋 Câu hỏi
                                </a>
        
                                <a class="btn btn-secondary" href="{{ route('admin.exams.edit', $exam) }}">Sửa</a>
                                <form method="POST" action="{{ route('admin.exams.destroy', $exam) }}" onsubmit="return confirm('Xoá exam này?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger">Xoá</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-4 text-center">Chưa có exam.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">{{ $exams->links() }}</div>
    </div>
</x-app-layout>
