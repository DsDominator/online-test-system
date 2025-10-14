<x-app-layout>
<x-slot name="header"><h2 class="font-semibold text-xl">Kết quả bài thi</h2></x-slot>
<div class="py-6 max-w-6xl mx-auto">
<form class="mb-4 flex gap-2">
<input type="number" name="exam_id" value="{{ request('exam_id') }}" placeholder="Exam ID" class="border p-2 rounded">
<input type="number" name="user_id" value="{{ request('user_id') }}" placeholder="User ID" class="border p-2 rounded">
<button class="btn btn-secondary">Lọc</button>
</form>


<table class="w-full border">
<thead><tr class="bg-gray-100">
<th class="p-2 border">Exam</th>
<th class="p-2 border">Student</th>
<th class="p-2 border">Score</th>
<th class="p-2 border">Submitted</th>
<th class="p-2 border"></th>
</tr></thead>
<tbody>
@foreach($results as $r)
<tr>
<td class="p-2 border">{{ $r->exam->title }}</td>
<td class="p-2 border">{{ $r->user->name }} (#{{ $r->user->id }})</td>
<td class="p-2 border">{{ $r->total_score }}</td>
<td class="p-2 border">{{ optional($r->submitted_at)->format('d/m/Y H:i') }}</td>
<td class="p-2 border text-right">
<a class="btn btn-primary" href="{{ route('admin.results.show', [$r->exam_id, $r->user_id]) }}">Chi tiết</a>
</td>
</tr>
@endforeach
</tbody>
</table>


<div class="mt-4">{{ $results->links() }}</div>
</div>
</x-app-layout>