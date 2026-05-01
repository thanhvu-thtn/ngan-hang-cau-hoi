<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Import câu hỏi từ Word
            </h2>
            <a href="{{ route('questions.index') }}"
                class="text-sm text-gray-600 hover:text-gray-800 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Quay lại danh sách
            </a>
        </div>
    </x-slot>

    {{-- THÔNG BÁO LỖI VALIDATION --}}
    @if ($errors->any())
        <div class="mb-6 flex items-start p-4 text-red-800 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm"
            role="alert">
            <svg class="flex-shrink-0 w-5 h-5 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
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
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    @endif

    {{-- THÔNG BÁO KHÁC (warning, error từ redirect) --}}
    @foreach (['warning' => 'yellow', 'error' => 'red'] as $type => $color)
        @if (session($type))
            <div class="mb-6 flex items-center p-4 text-{{ $color }}-800 bg-{{ $color }}-50 border-l-4 border-{{ $color }}-500 rounded-r-lg shadow-sm">
                <span class="text-sm font-medium">{{ session($type) }}</span>
            </div>
        @endif
    @endforeach

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ============================================================ --}}
            {{-- FORM UPLOAD                                                   --}}
            {{-- ============================================================ --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-lg font-bold text-blue-800 mb-1 flex items-center">
                    <span
                        class="bg-blue-100 text-blue-800 rounded-full w-7 h-7 flex items-center justify-center mr-2 text-sm">1</span>
                    Tải file Word lên
                </h3>
                <p class="text-sm text-gray-500 mb-5 ml-9">Chỉ chấp nhận file <code class="bg-gray-100 px-1 rounded">.docx</code>, tối đa 10 MB.</p>

                <form action="{{ route('question-imports.store') }}" method="POST"
                    enctype="multipart/form-data" id="upload-form">
                    @csrf

                    {{-- Drop zone --}}
                    <label for="word_file"
                        class="flex flex-col items-center justify-center w-full h-44 border-2 border-dashed rounded-xl cursor-pointer transition
                               border-gray-300 bg-gray-50 hover:border-blue-400 hover:bg-blue-50"
                        id="drop-zone">
                        {{-- Icon trạng thái --}}
                        <div id="dz-idle" class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p class="text-sm font-medium">Kéo thả file vào đây, hoặc <span class="text-blue-600 underline">chọn file</span></p>
                            <p class="text-xs">.docx · tối đa 10 MB</p>
                        </div>
                        {{-- Hiển thị khi đã chọn file --}}
                        <div id="dz-selected" class="hidden flex-col items-center gap-2 text-blue-700">
                            <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p id="dz-filename" class="text-sm font-bold"></p>
                            <p id="dz-filesize" class="text-xs text-gray-500"></p>
                        </div>
                    </label>
                    <input type="file" name="word_file" id="word_file" accept=".docx" class="hidden">

                    @error('word_file')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    {{-- Submit --}}
                    <div class="mt-6 flex justify-end">
                        <button type="submit" id="submit-btn"
                            class="inline-flex items-center gap-2 px-8 py-3 bg-blue-700 text-white font-bold rounded-lg hover:bg-blue-800 shadow transition active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <span id="submit-label">Tải lên & Xem trước</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- ============================================================ --}}
            {{-- HƯỚNG DẪN ĐỊNH DẠNG FILE                                     --}}
            {{-- ============================================================ --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                    <span
                        class="bg-blue-100 text-blue-800 rounded-full w-7 h-7 flex items-center justify-center mr-2 text-sm">2</span>
                    Hướng dẫn định dạng file
                </h3>

                <div class="space-y-4 text-sm text-gray-700 ml-9">

                    <p>File Word phải chứa các <strong>bảng 2 cột</strong> (Key | Value). Hệ thống đọc từng dòng theo thứ tự từ trên xuống.</p>

                    {{-- Câu hỏi tự do --}}
                    <div>
                        <p class="font-semibold text-gray-800 mb-1">📌 Câu hỏi tự do (không có ngữ cảnh dùng chung):</p>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full text-xs">
                                <thead class="bg-gray-100 text-gray-600 font-bold">
                                    <tr><th class="px-4 py-2 text-left">Key</th><th class="px-4 py-2 text-left">Value</th></tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ([
                                        ['begin',                'question'],
                                        ['question_code',        'VL11-001'],
                                        ['question_type_code',   'MC  (MC / TF / SA / ES)'],
                                        ['cognitive_level_code', 'NB'],
                                        ['objective_codes',      '11-1CB-001-001-001 # 11-1CB-001-001-002'],
                                        ['stem',                 'Nội dung câu hỏi (có thể chứa ảnh, công thức)'],
                                        ['choice1',              'Đáp án A'],
                                        ['choice2',              'Đáp án B'],
                                        ['choice3',              'Đáp án C'],
                                        ['choice4',              'Đáp án D'],
                                        ['answer',               '2  (MC: 1/2/3/4 hoặc A/B/C/D · TF: đúng/sai · SA: số ≤4 ký tự)'],
                                        ['explanation',          'Lời giải (tuỳ chọn)'],
                                        ['end',                  'question'],
                                    ] as [$k, $v])
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-1.5 font-mono text-blue-700 whitespace-nowrap">{{ $k }}</td>
                                            <td class="px-4 py-1.5 text-gray-600">{{ $v }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Câu hỏi có shared context --}}
                    <div>
                        <p class="font-semibold text-gray-800 mb-1">📌 Câu hỏi có dữ liệu dùng chung:</p>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full text-xs">
                                <thead class="bg-gray-100 text-gray-600 font-bold">
                                    <tr><th class="px-4 py-2 text-left">Key</th><th class="px-4 py-2 text-left">Value</th></tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ([
                                        ['begin',               'shared_context'],
                                        ['shared_context_code', 'SC-VL11-001'],
                                        ['description',         'Mô tả ngữ cảnh (tuỳ chọn)'],
                                        ['content',             'Nội dung ngữ cảnh dùng chung (có thể chứa ảnh, công thức)'],
                                        ['begin',               'question'],
                                        ['...',                 '(các trường câu hỏi như trên)'],
                                        ['end',                 'question'],
                                        ['begin',               'question  ← câu hỏi thứ 2, 3, ...'],
                                        ['...',                 '...'],
                                        ['end',                 'question'],
                                        ['end',                 'shared_context'],
                                    ] as [$k, $v])
                                        <tr class="{{ str_starts_with($v, 'shared_context') || $v === 'question' || str_starts_with($k, 'begin') || str_starts_with($k, 'end') ? 'bg-blue-50 font-semibold' : 'hover:bg-gray-50' }}">
                                            <td class="px-4 py-1.5 font-mono text-blue-700 whitespace-nowrap">{{ $k }}</td>
                                            <td class="px-4 py-1.5 text-gray-600">{{ $v }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Ghi chú --}}
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-amber-800 text-xs space-y-1">
                        <p class="font-bold">⚠️ Lưu ý:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            <li>Mỗi câu hỏi hoặc ngữ cảnh dùng chung nằm trong <strong>một bảng riêng</strong> hoặc là các dòng liên tiếp trong cùng một bảng.</li>
                            <li>Các giá trị <code>begin</code> / <code>end</code> phân biệt các block, hệ thống đọc theo thứ tự từ trên xuống.</li>
                            <li>Nhiều objective_codes ngăn cách nhau bằng dấu <code>#</code>.</li>
                            <li>Ảnh và công thức toán trong các ô nội dung sẽ được giữ nguyên.</li>
                        </ul>
                    </div>

                    {{-- Tải file mẫu 'storage/word-imports/question-import-template.docx' --}}
                    <div class="pt-1">
                        <a href="{{ asset('storage/word-imports/question-import-template.docx') }}"
                            download
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-xs font-bold rounded-lg hover:bg-green-700 transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Tải file mẫu (.docx)
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>

<script>
    const input    = document.getElementById('word_file');
    const dropZone = document.getElementById('drop-zone');
    const dzIdle   = document.getElementById('dz-idle');
    const dzSel    = document.getElementById('dz-selected');
    const dzName   = document.getElementById('dz-filename');
    const dzSize   = document.getElementById('dz-filesize');
    const submitBtn= document.getElementById('submit-btn');
    const submitLbl= document.getElementById('submit-label');

    function formatBytes(bytes) {
        return bytes < 1024 * 1024
            ? (bytes / 1024).toFixed(1) + ' KB'
            : (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    function showFile(file) {
        if (!file) return;
        dzIdle.classList.add('hidden');
        dzSel.classList.remove('hidden');
        dzSel.classList.add('flex');
        dzName.textContent = file.name;
        dzSize.textContent = formatBytes(file.size);
        submitBtn.disabled = false;
    }

    input.addEventListener('change', () => showFile(input.files[0]));

    // Drag & drop
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('border-blue-400', 'bg-blue-50'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-blue-400', 'bg-blue-50'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');
        const file = e.dataTransfer.files[0];
        if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            showFile(file);
        }
    });

    // Loading state khi submit
    document.getElementById('upload-form').addEventListener('submit', () => {
        submitBtn.disabled = true;
        submitLbl.textContent = 'Đang xử lý...';
    });
</script>
