@php
  $optionsText = '';
  if (isset($question) && is_array($question->options)) {
      $optionsText = implode("\n", $question->options);
  }
@endphp

<div class="mb-3">
    <label class="block font-semibold">Loại câu hỏi</label>
    <select name="type" id="type" class="border p-2 w-full rounded">
        <option value="multiple_choice" {{ old('type', $question->type ?? 'multiple_choice')=='multiple_choice' ? 'selected' : '' }}>Trắc nghiệm</option>
        <option value="essay" {{ old('type', $question->type ?? '')=='essay' ? 'selected' : '' }}>Tự luận</option>
    </select>
    @error('type')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="block font-semibold">Nội dung câu hỏi</label>
    <textarea name="question_text" rows="3" class="border p-2 w-full rounded">{{ old('question_text', $question->question_text ?? '') }}</textarea>
    @error('question_text')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
</div>

<div id="mcqArea" class="mb-3" style="{{ old('type', $question->type ?? 'multiple_choice')==='multiple_choice' ? '' : 'display:none;' }}">
    <label class="block font-semibold">Các lựa chọn (mỗi dòng 1)</label>
    <textarea name="options_text" rows="4" class="border p-2 w-full rounded">{{ old('options_text', $optionsText) }}</textarea>
    @error('options')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror

    <label class="block font-semibold mt-2">Đáp án đúng</label>
    <input name="correct_answer" class="border p-2 w-full rounded" value="{{ old('correct_answer', $question->correct_answer ?? '') }}" placeholder="Phải khớp một lựa chọn">
    @error('correct_answer')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
</div>

<script>
    const typeSel = document.getElementById('type');
    const mcq = document.getElementById('mcqArea');
    if (typeSel) {
        typeSel.addEventListener('change', () => {
            mcq.style.display = typeSel.value === 'multiple_choice' ? 'block' : 'none';
        });
    }
</script>
