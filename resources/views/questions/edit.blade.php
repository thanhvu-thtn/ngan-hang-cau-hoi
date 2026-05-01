<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Chỉnh sửa câu hỏi: <span class="text-blue-700">{{ $question->code }}</span>
            </h2>
            @if ($navigation['source'] === 'shared_context' && $navigation['shared_context_id'])
                <a href="{{ route('shared-contexts.show', $navigation['shared_context_id']) }}"
                    class="text-sm text-gray-600 hover:text-gray-800 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Quay lại dữ liệu dùng chung
                </a>
            @else
                <a href="{{ route('questions.show', $question) }}"
                    class="text-sm text-gray-600 hover:text-gray-800 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Quay lại chi tiết
                </a>
            @endif
        </div>
    </x-slot>

    {{-- THÔNG BÁO LỖI --}}
    @if ($errors->any())
        <div class="mb-6 flex items-start p-4 text-red-800 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm"
            role="alert">
            <svg class="flex-shrink-0 w-5 h-5 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd"></path>
            </svg>
            <div class="ml-3">
                <span class="text-sm font-bold">Đã có lỗi xảy ra:</span>
                <ul class="mt-1.5 ml-4 list-disc list-inside text-xs font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button"
                class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg p-1.5 hover:bg-red-100 inline-flex h-8 w-8"
                onclick="this.parentElement.remove()">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form action="{{ route('questions.update', $question) }}" method="POST" id="main-form">
                @csrf
                @method('PUT')

                {{-- Hidden: điều hướng sau khi update --}}
                <input type="hidden" name="nav_source"            value="{{ $navigation['source'] }}">
                <input type="hidden" name="nav_shared_context_id" value="{{ $navigation['shared_context_id'] }}">

                {{-- Hidden: shared_context_id (data field thực sự lưu vào DB) --}}
                <input type="hidden" name="shared_context_id" value="{{ $question->shared_context_id }}">

                {{-- Hidden: question_type_id (được JS fill) --}}
                <input type="hidden" name="question_type_id" id="hidden_question_type_id"
                    value="{{ old('question_type_id', $question->question_type_id) }}">

                {{-- Hidden: question_status_id (giữ nguyên status hiện tại) --}}
                <input type="hidden" name="question_status_id"
                    value="{{ old('question_status_id', $question->question_status_id) }}">

                <div class="space-y-6">

                    {{-- =============================================================== --}}
                    {{-- PHẦN 1: THÔNG TIN CƠ BẢN                                       --}}
                    {{-- =============================================================== --}}
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                            <span
                                class="bg-blue-100 text-blue-800 rounded-full w-7 h-7 flex items-center justify-center mr-2">1</span>
                            Thông tin cơ bản
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Mã câu hỏi --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Mã câu hỏi <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="code"
                                    value="{{ old('code', $question->code) }}" required
                                    class="mt-1 block px-4 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Loại câu hỏi --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kiểu câu hỏi <span
                                        class="text-red-500">*</span></label>
                                <select name="question_type_code" id="question_type_code" required
                                    onchange="toggleQuestionBlocks(this.value); updateQuestionTypeId(this.value)"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">-- Chọn loại --</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->code }}"
                                            data-id="{{ $type->id }}"
                                            {{ old('question_type_code', $question->type?->code) == $type->code ? 'selected' : '' }}>
                                            {{ $type->name }} ({{ $type->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Mức độ nhận thức --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Mức độ nhận thức</label>
                                <select name="cognitive_level_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @foreach ($levels as $level)
                                        <option value="{{ $level->id }}"
                                            {{ old('cognitive_level_id', $question->cognitive_level_id) == $level->id ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Trạng thái & Mô tả --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Trạng thái</label>

                                @can('approve-exams')
                                    {{-- Có quyền: hiện combobox để thay đổi --}}
                                    <select name="question_status_id"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status->id }}"
                                                {{ old('question_status_id', $question->question_status_id) == $status->id ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    {{-- Không có quyền: chỉ hiển thị, giữ nguyên giá trị qua hidden input --}}
                                    <input type="hidden" name="question_status_id"
                                        value="{{ $question->question_status_id }}">
                                    @php
                                        $statusColors = [
                                            'PENDING'  => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'APPROVED' => 'bg-green-100 text-green-800 border-green-200',
                                            'REJECTED' => 'bg-red-100 text-red-800 border-red-200',
                                        ];
                                        $sc = $statusColors[$question->status?->code] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                    @endphp
                                    <div class="mt-1 flex items-center gap-2">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-md border text-sm font-semibold {{ $sc }}">
                                            {{ $question->status?->name ?? '—' }}
                                        </span>
                                        <span class="text-xs text-gray-400 italic">Bạn không có quyền thay đổi trạng thái.</span>
                                    </div>
                                @endcan
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Mô tả ngắn</label>
                                <input type="text" name="description"
                                    value="{{ old('description', $question->description) }}"
                                    class="mt-1 px-4 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>

                        {{-- Objective Selector --}}
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider text-xs">
                                    Yêu cầu cần đạt (Objectives)
                                </label>
                                <button type="button"
                                    onclick="document.getElementById('objective-modal').classList.remove('hidden')"
                                    class="text-xs bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">
                                    + Chọn từ danh sách
                                </button>
                            </div>
                            {{-- Danh sách objectives đã chọn (pre-filled từ DB) --}}
                            <div id="selected-objectives-list" class="space-y-2">
                                @if ($question->objectives->isEmpty())
                                    <p class="text-gray-400 text-xs italic">Chưa có mục tiêu nào được chọn.</p>
                                @else
                                    @foreach ($question->objectives as $objective)
                                        <div class="flex items-start justify-between bg-white p-3 rounded border border-gray-200 shadow-sm mb-2 hover:border-blue-300 transition-colors">
                                            <div class="text-sm format-katex font-medium text-gray-800 flex-1 break-words leading-relaxed">
                                                {!! $objective->description ?? $objective->code !!}
                                            </div>
                                            <input type="hidden" name="objective_ids[]" value="{{ $objective->id }}">
                                            <button type="button" onclick="this.parentElement.remove()"
                                                class="text-red-400 hover:text-red-600 font-bold ml-4 flex-shrink-0 text-xl leading-none">&times;</button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- =============================================================== --}}
                    {{-- PHẦN 2: PHẦN DẪN (STEM)                                        --}}
                    {{-- =============================================================== --}}
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                            <span
                                class="bg-blue-100 text-blue-800 rounded-full w-7 h-7 flex items-center justify-center mr-2">2</span>
                            Phần dẫn (Stem)
                        </h3>
                        <div class="mb-2 flex space-x-2">
                            <button type="button" onclick="initTinyMCE('stem')"
                                class="text-xs px-2 py-1 bg-gray-200 rounded">Bật soạn thảo</button>
                            <button type="button" onclick="tinymce.execCommand('mceRemoveEditor', false, 'stem')"
                                class="text-xs px-2 py-1 bg-red-50 text-red-600 rounded">Tắt</button>
                            <button type="button" onclick="showPreview('stem')"
                                class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded font-bold">Xem trước</button>
                        </div>
                        <textarea id="stem" name="stem" rows="6"
                            class="w-full border-gray-300 rounded-md shadow-sm">{{ old('stem', $question->stem) }}</textarea>
                        @error('stem')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- =============================================================== --}}
                    {{-- PHẦN ĐỘNG: CÁC LỰA CHỌN THEO LOẠI CÂU HỎI                     --}}
                    {{-- =============================================================== --}}
                    <div id="choices-container" class="space-y-6">

                        {{-- ---- BLOCK MC ---- --}}
                        <div id="block-MC"
                            class="type-block hidden bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                            <h4 class="font-bold text-gray-700 border-b pb-2 mb-4">Danh sách đáp án (MC)</h4>

                            @php
                                // Pre-fill choices từ DB hoặc old()
                                $dbChoices = $question->choices->values();
                            @endphp

                            <div id="mc-sortable" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach (range(0, 3) as $index)
                                    @php
                                        $label     = chr(65 + $index);
                                        $dbChoice  = $dbChoices->get($index);
                                        $oldContent = old("choices.$index.content", $dbChoice?->content ?? '');
                                        $oldIsTrue  = old("choices.$index.is_true",  $dbChoice?->is_true  ?? false);
                                    @endphp
                                    <div class="mc-choice-item p-4 border rounded-lg bg-gray-50 relative"
                                        data-index="{{ $index }}">

                                        <input type="hidden" name="choices[{{ $index }}][order_index]"
                                            value="{{ $index + 1 }}">

                                        {{-- Header: nhãn + radio đáp án đúng --}}
                                        <div class="flex justify-between items-center mb-2">
                                            <label class="font-semibold text-blue-700 choice-label">
                                                Lựa chọn {{ $label }}
                                            </label>
                                            <div class="flex items-center">
                                                <input type="hidden"
                                                    name="choices[{{ $index }}][is_true]"
                                                    id="hidden_is_true_mc_{{ $index }}"
                                                    value="{{ $oldIsTrue ? '1' : '0' }}">
                                                <input type="radio" name="mc_correct_answer"
                                                    value="{{ $index }}"
                                                    @checked($oldIsTrue)
                                                    onchange="
                                                        document.querySelectorAll('input[id^=hidden_is_true_mc_]').forEach(el => el.value = '0');
                                                        document.getElementById('hidden_is_true_mc_{{ $index }}').value = '1';
                                                    "
                                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                                <span class="ml-2 text-sm text-gray-600">Đáp án đúng</span>
                                            </div>
                                        </div>

                                        {{-- Toolbar soạn thảo (giống stem) --}}
                                        <div class="mb-1 flex space-x-2">
                                            <button type="button"
                                                onclick="initTinyMCE('choice_{{ $index }}')"
                                                class="text-xs px-2 py-1 bg-gray-200 rounded hover:bg-gray-300">Bật soạn thảo</button>
                                            <button type="button"
                                                onclick="tinymce.execCommand('mceRemoveEditor', false, 'choice_{{ $index }}')"
                                                class="text-xs px-2 py-1 bg-red-50 text-red-600 rounded hover:bg-red-100">Tắt</button>
                                            <button type="button"
                                                onclick="showPreview('choice_{{ $index }}')"
                                                class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded font-bold hover:bg-green-200">Xem trước</button>
                                        </div>

                                        {{-- Textarea với id duy nhất --}}
                                        <textarea id="choice_{{ $index }}"
                                            name="choices[{{ $index }}][content]"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            rows="3">{{ $oldContent }}</textarea>
                                    </div>
                                @endforeach
                            </div>

                            @error('choices')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            {{-- Layout & Tỉ lệ --}}
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 border-t pt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Dàn trang câu hỏi
                                        <span class="text-gray-400 text-xs font-normal">(tuỳ chọn)</span>
                                    </label>
                                    <select name="question_layout_id"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="">-- Không dàn trang --</option>
                                        @foreach ($layouts as $layout)
                                            <option value="{{ $layout->id }}"
                                                {{ old('question_layout_id', $question->question_layout_id) == $layout->id ? 'selected' : '' }}>
                                                {{ $layout->name }} ({{ $layout->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('question_layout_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Tỉ lệ dàn trang
                                        <span class="text-gray-400 text-xs font-normal">(0 – 1, tuỳ chọn)</span>
                                    </label>
                                    <input type="number" name="layout_ratio"
                                        value="{{ old('layout_ratio', $question->layout_ratio) }}"
                                        min="0" max="1" step="0.05" placeholder="VD: 0.5"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-4">
                                    @error('layout_ratio')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ---- BLOCK TF ---- --}}
                        <div id="block-TF"
                            class="type-block hidden bg-white p-6 rounded-xl shadow-sm border border-gray-200 text-center">
                            <h4 class="font-bold text-gray-700 mb-4 text-left">Chọn đáp án đúng (TF)</h4>
                            @php
                                // TF lưu content là 'Đúng' hoặc 'Sai'
                                $tfValue = old('tf_choice');
                                if ($tfValue === null && $question->type?->code === 'TF') {
                                    $tfChoice = $question->choices->first();
                                    $tfValue  = $tfChoice ? ($tfChoice->content === 'Đúng' ? '1' : '0') : '1';
                                }
                                $tfValue = $tfValue ?? '1';
                            @endphp
                            <div class="flex justify-center space-x-10">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="tf_choice" value="1"
                                        {{ $tfValue == '1' ? 'checked' : '' }}
                                        class="w-5 h-5 text-green-600">
                                    <span class="ml-2 font-bold text-green-700 text-lg">ĐÚNG</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="tf_choice" value="0"
                                        {{ $tfValue == '0' ? 'checked' : '' }}
                                        class="w-5 h-5 text-red-600">
                                    <span class="ml-2 font-bold text-red-700 text-lg">SAI</span>
                                </label>
                            </div>
                        </div>

                        {{-- ---- BLOCK SA ---- --}}
                        <div id="block-SA"
                            class="type-block hidden bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                            <h4 class="font-bold text-gray-700 mb-2">Đáp án ngắn (Số)</h4>
                            <p class="text-xs text-gray-500 mb-3 italic">Chỉ nhập số, dấu âm (-), dấu phẩy (,). Tối đa 4 ký tự.</p>
                            @php
                                $saValue = old('sa_choice');
                                if ($saValue === null && $question->type?->code === 'SA') {
                                    $saValue = $question->choices->first()?->content ?? '';
                                }
                            @endphp
                            <input type="text" name="sa_choice" maxlength="4"
                                value="{{ $saValue }}"
                                oninput="this.value = this.value.replace(/[^0-9,-]/g, '')"
                                class="w-32 text-center text-xl font-bold tracking-widest border-2 border-blue-500 rounded-lg py-2 focus:ring-0">
                            @error('sa_choice')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ---- BLOCK ES (không có choices) ---- --}}
                        <div id="block-ES"
                            class="type-block hidden bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                            <h4 class="font-bold text-gray-700 mb-2">Câu hỏi tự luận (Essay)</h4>
                            <p class="text-sm text-gray-500 italic">Câu hỏi tự luận không có đáp án cố định. Hướng dẫn chấm nằm ở phần lời giải bên dưới.</p>
                        </div>

                    </div>{{-- /choices-container --}}

                    {{-- =============================================================== --}}
                    {{-- PHẦN 3: LỜI GIẢI                                               --}}
                    {{-- =============================================================== --}}
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                            <span
                                class="bg-blue-100 text-blue-800 rounded-full w-7 h-7 flex items-center justify-center mr-2">3</span>
                            Hướng dẫn giải / Giải thích
                        </h3>
                        <div class="mb-2 flex space-x-2">
                            <button type="button" onclick="initTinyMCE('explanation')"
                                class="text-xs px-2 py-1 bg-gray-200 rounded">Bật soạn thảo</button>
                            <button type="button" onclick="showPreview('explanation')"
                                class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded font-bold">Xem trước</button>
                        </div>
                        <textarea id="explanation" name="explanation" rows="4"
                            class="w-full border-gray-300 rounded-md shadow-sm">{{ old('explanation', $question->explanation) }}</textarea>
                    </div>

                    {{-- NÚT HÀNH ĐỘNG --}}
                    <div class="flex justify-between items-center pb-12">
                        @if ($navigation['source'] === 'shared_context' && $navigation['shared_context_id'])
                            <a href="{{ route('shared-contexts.show', $navigation['shared_context_id']) }}"
                                class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">
                                Hủy bỏ
                            </a>
                        @else
                            <a href="{{ route('questions.show', $question) }}"
                                class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">
                                Hủy bỏ
                            </a>
                        @endif
                        <button type="submit"
                            class="px-10 py-3 bg-blue-700 text-white font-bold rounded-lg hover:bg-blue-800 shadow-lg transform transition active:scale-95">
                            CẬP NHẬT CÂU HỎI
                        </button>
                    </div>

                </div>{{-- /space-y-6 --}}
            </form>
        </div>
    </div>

    {{-- =============================================================== --}}
    {{-- MODAL CHỌN OBJECTIVES                                           --}}
    {{-- =============================================================== --}}
    <div id="objective-modal"
        class="hidden fixed inset-0 z-50 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center overflow-hidden">
        <div
            class="bg-white rounded-xl shadow-2xl w-[95vw] h-[95vh] flex flex-col border border-gray-200 overflow-hidden">
            <div class="p-4 border-b flex justify-between items-center bg-white flex-shrink-0">
                <h3 class="font-bold text-xl text-gray-800">Chọn Yêu cầu cần đạt</h3>
                <button type="button"
                    onclick="document.getElementById('objective-modal').classList.add('hidden')"
                    class="text-gray-400 hover:text-red-500 p-2 text-2xl transition">&times;</button>
            </div>
            <div class="p-6 overflow-y-auto flex-grow bg-gray-50">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 min-h-full">
                    {{--
                        Truyền thêm $selectedObjectiveIds để component tự pre-check
                        Nếu component chưa hỗ trợ prop này, xem phần JS bên dưới
                        sẽ tự check các checkbox tương ứng sau DOMContentLoaded
                    --}}
                    <x-treeview.objective-selector :items="$treeData" id_prefix="edit_"
                        :selected="$selectedObjectiveIds" />
                </div>
            </div>
            <div class="p-4 border-t bg-white flex justify-end space-x-3 flex-shrink-0">
                <button type="button"
                    onclick="document.getElementById('objective-modal').classList.add('hidden')"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                    Hủy bỏ
                </button>
                <button type="button" onclick="applyObjectives()"
                    class="px-8 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition shadow-md">
                    Xác nhận chọn
                </button>
            </div>
        </div>
    </div>

    {{-- COMPONENT EDITOR --}}
    @include('components.tinymce.editor')

</x-app-layout>

{{-- =============================================================== --}}
{{-- JAVASCRIPT                                                       --}}
{{-- =============================================================== --}}
<script>
    // ── Dữ liệu PHP → JS ──────────────────────────────────────────
    const questionTypesData      = @json($types);
    const selectedObjectiveIds   = @json($selectedObjectiveIds);   // IDs đã lưu trong DB
    const currentTypeCode        = @json(old('question_type_code', $question->type?->code));

    // ── Toggle block theo loại câu hỏi ────────────────────────────
    function toggleQuestionBlocks(typeCode) {
        document.querySelectorAll('.type-block').forEach(b => b.classList.add('hidden'));
        if (typeCode) {
            const block = document.getElementById('block-' + typeCode);
            if (block) block.classList.remove('hidden');
        }
    }

    // ── Cập nhật hidden question_type_id ──────────────────────────
    function updateQuestionTypeId(code) {
        const found = document.querySelector(`#question_type_code option[value="${code}"]`);
        if (found) {
            document.getElementById('hidden_question_type_id').value = found.dataset.id;
        }
    }

    // ── Apply objectives từ modal ──────────────────────────────────
    function applyObjectives() {
        const modal             = document.getElementById('objective-modal');
        const selectedContainer = document.getElementById('selected-objectives-list');
        const checkedBoxes      = modal.querySelectorAll('input[type="checkbox"]:checked');

        selectedContainer.innerHTML = '';

        if (checkedBoxes.length === 0) {
            selectedContainer.innerHTML = '<p class="text-gray-400 text-xs italic">Chưa có mục tiêu nào được chọn.</p>';
        } else {
            checkedBoxes.forEach(cb => {
                const labelNode = cb.closest('label') || cb.parentElement;
                let clone       = labelNode.cloneNode(true);
                clone.querySelectorAll('input, .btn-toggle, svg').forEach(el => el.remove());
                const labelHTML = clone.innerHTML.trim();
                const id        = cb.value;

                const row       = document.createElement('div');
                row.className   = 'flex items-start justify-between bg-white p-3 rounded border border-gray-200 shadow-sm mb-2 hover:border-blue-300 transition-colors';
                row.innerHTML   = `
                    <div class="text-sm font-medium text-gray-800 flex-1 break-words leading-relaxed">${labelHTML}</div>
                    <input type="hidden" name="objective_ids[]" value="${id}">
                    <button type="button" onclick="this.parentElement.remove()"
                        class="text-red-400 hover:text-red-600 font-bold ml-4 flex-shrink-0 text-xl leading-none">&times;</button>
                `;
                selectedContainer.appendChild(row);
            });

            if (window.renderMathInElement) {
                window.renderMathInElement(selectedContainer, {
                    delimiters: [
                        { left: '$$', right: '$$', display: true },
                        { left: '$',  right: '$',  display: false },
                        { left: '\\(', right: '\\)', display: false },
                        { left: '\\[', right: '\\]', display: true }
                    ],
                    throwOnError: false
                });
            }
        }

        modal.classList.add('hidden');
    }

    // ── Khởi tạo khi DOM sẵn sàng ─────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {

        // 1. Hiển thị đúng block theo loại câu hỏi hiện tại
        if (currentTypeCode) {
            toggleQuestionBlocks(currentTypeCode);
            updateQuestionTypeId(currentTypeCode);
        }

        // 2. Pre-check các checkbox trong modal theo selectedObjectiveIds
        //    (Fallback nếu component treeview chưa hỗ trợ prop :selected)
        if (selectedObjectiveIds && selectedObjectiveIds.length > 0) {
            selectedObjectiveIds.forEach(id => {
                const cb = document.querySelector(`#objective-modal input[type="checkbox"][value="${id}"]`);
                if (cb) cb.checked = true;
            });
        }

        // 3. Lắng nghe thay đổi loại câu hỏi
        document.getElementById('question_type_code').addEventListener('change', function () {
            toggleQuestionBlocks(this.value);
            updateQuestionTypeId(this.value);
        });
    });
</script>
