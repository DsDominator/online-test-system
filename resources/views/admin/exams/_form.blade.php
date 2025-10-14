@csrf
<div class="mb-4">
    <label class="block mb-1 font-medium">Tiêu đề</label>
    <input type="text" name="title" value="{{ old('title', $exam->title ?? '') }}" class="border p-2 rounded w-full">
    @error('title') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
</div>

<div class="mb-4">
    <label class="block mb-1 font-medium">Thời lượng (phút)</label>
    <input type="number" name="duration" min="1" max="300" value="{{ old('duration', $exam->duration ?? 45) }}" class="border p-2 rounded w-full">
    @error('duration') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
</div>

