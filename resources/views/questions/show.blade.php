<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Chi tiết câu hỏi: <span class="text-blue-700">{{ $question->code }}</span>
            </h2>
            <div class="flex items-center gap-2">
                {{-- Nút Quay lại động --}}
                @if ($navigation['source'] === 'shared_context' && $navigation['shared_context_id'])
                    <a href="{{ route('shared-contexts.show', $navigation['shared_context_id']) }}"
                        class="text-sm text-gray-600 hover:text-gray-800 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Quay lại dữ liệu dùng chung
                    </a>
                @else
                    <a href="{{ route('questions.index') }}"
                        class="text-sm text-gray-600 hover:text-gray-800 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Quay lại danh sách
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- PHẦN 1: THÔNG TIN CƠ BẢN --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                    <span
                        class="bg-blue-100 text-blue-800 rounded-full w-7 h-7 flex items-center justify-center mr-2">1</span>
                    Thông tin cơ bản
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Mã câu hỏi --}}
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Mã câu hỏi</p>
                        <p class="mt-1 text-base font-semibold text-gray-900">{{ $question->code }}</p>
                    </div>

                    {{-- Loại câu hỏi --}}
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Kiểu câu hỏi</p>
                        <p class="mt-1">
                            @php
                                $typeColors = [
                                    'MC' => 'bg-blue-100 text-blue-800',
                                    'TF' => 'bg-purple-100 text-purple-800',
                                    'SA' => 'bg-orange-100 text-orange-800',
                                    'ES' => 'bg-green-100 text-green-800',
                                ];
                                $typeColor = $typeColors[$question->type?->code] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $typeColor }}">
                                {{ $question->type?->name ?? 'N/A' }}
                                @if ($question->type?->code)
                                    ({{ $question->type->code }})
                                @endif
                            </span>
                        </p>
                    </div>

                    {{-- Mức độ nhận thức --}}
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Mức độ nhận thức</p>
                        <p class="mt-1 text-base text-gray-900">{{ $question->cognitiveLevel?->name ?? '—' }}</p>
                    </div>
                </div>

                {{-- Trạng thái & Mô tả --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</p>
                        <p class="mt-1">
                            @php
                                $statusColors = [
                                    'PENDING' => 'bg-yellow-100 text-yellow-800',
                                    'APPROVED' => 'bg-green-100 text-green-800',
                                    'REJECTED' => 'bg-red-100 text-red-800',
                                ];
                                $statusCode = $question->status?->code;
                                $statusColor = $statusColors[$statusCode] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $statusColor }}">
                                {{ $question->status?->name ?? '—' }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Mô tả ngắn</p>
                        <p class="mt-1 text-base text-gray-900">{{ $question->description ?: '—' }}</p>
                    </div>
                </div>

                {{-- Objectives --}}
                @if ($question->objectives && $question->objectives->count() > 0)
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <p class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">
                            Yêu cầu cần đạt (Objectives)
                        </p>
                        <div class="space-y-2">
                            @foreach ($question->objectives as $objective)
                                <div class="flex items-start bg-white p-3 rounded border border-gray-200 shadow-sm">
                                    <svg class="w-4 h-4 text-blue-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm text-gray-800 format-katex leading-relaxed">
                                        {!! $objective->description ?? $objective->code !!}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <p class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                            Yêu cầu cần đạt (Objectives)
                        </p>
                        <p class="text-gray-400 text-sm italic">Chưa gắn mục tiêu nào.</p>
                    </div>
                @endif
            </div>

            {{-- PHẦN 2: PHẦN DẪN (STEM) --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                    <span
                        class="bg-blue-100 text-blue-800 rounded-full w-7 h-7 flex items-center justify-center mr-2">2</span>
                    Phần dẫn (Stem)
                </h3>
                <div
                    class="prose max-w-none format-katex text-gray-900 bg-gray-50 rounded-lg p-4 border border-gray-100 min-h-[60px] stem-content">
                    {!! $question->stem !!}
                </div>
                <style>
                    /* Ngăn hình ảnh TinyMCE tràn ra ngoài container */
                    .stem-content img {
                        max-width: 100% !important;
                        height: auto !important;
                        display: block;
                    }

                    /* Clear float nếu TinyMCE chèn hình dạng float */
                    .stem-content::after {
                        content: "";
                        display: table;
                        clear: both;
                    }

                    /* Nếu ảnh được wrap trong p hoặc div có float */
                    .stem-content p,
                    .stem-content div {
                        max-width: 100%;
                        overflow: hidden;
                    }
                </style>
            </div>

            {{-- PHẦN 3: CÁC LỰA CHỌN / ĐÁP ÁN (hiển thị theo loại) --}}
            @php $typeCode = $question->type?->code; @endphp

            {{-- MC: Trắc nghiệm nhiều lựa chọn --}}
            @if ($typeCode === 'MC')
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                        <span
                            class="bg-blue-100 text-blue-800 rounded-full w-7 h-7 flex items-center justify-center mr-2">3</span>
                        Danh sách đáp án (MC)
                    </h3>

                    @if ($question->layout || $question->layout_ratio)
                        <div class="mb-4 flex flex-wrap items-center gap-4 text-sm text-gray-600">
                            @if ($question->layout)
                                <span class="bg-gray-100 px-3 py-1 rounded-full">
                                    Dàn trang: <strong>{{ $question->layout?->name }}
                                        ({{ $question->layout?->code }})</strong>
                                </span>
                            @endif
                            @if ($question->layout_ratio)
                                <span class="bg-gray-100 px-3 py-1 rounded-full">
                                    Tỉ lệ: <strong>{{ $question->layout_ratio }}</strong>
                                </span>
                            @endif
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($question->choices->sortBy('order_index') as $index => $choice)
                            @php $label = chr(65 + $index); @endphp
                            <div
                                class="p-4 border rounded-lg relative
                                {{ $choice->is_true ? 'bg-green-50 border-green-400' : 'bg-gray-50 border-gray-200' }}">

                                {{-- Nhãn lựa chọn --}}
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-semibold text-blue-700">Lựa chọn {{ $label }}</span>
                                    @if ($choice->is_true)
                                        <span
                                            class="inline-flex items-center gap-1 text-xs font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded-full">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Đáp án đúng
                                        </span>
                                    @endif
                                </div>

                                {{-- Nội dung đáp án --}}
                                <div class="prose prose-sm max-w-none format-katex text-gray-800">
                                    {!! $choice->content !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- TF: Đúng / Sai --}}
            @elseif ($typeCode === 'TF')
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                        <span
                            class="bg-blue-100 text-blue-800 rounded-full w-7 h-7 flex items-center justify-center mr-2">3</span>
                        Đáp án (Đúng / Sai)
                    </h3>
                    @php
                        $tfChoice = $question->choices->first();
                        $isTrue = $tfChoice?->is_true;
                    @endphp
                    <div class="flex justify-center gap-10 py-4">
                        <div class="flex flex-col items-center gap-2">
                            <div
                                class="w-16 h-16 rounded-full flex items-center justify-center
                                {{ $isTrue ? 'bg-green-500 text-white shadow-lg ring-4 ring-green-200' : 'bg-gray-100 text-gray-400' }}">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span
                                class="font-bold text-lg {{ $isTrue ? 'text-green-700' : 'text-gray-400' }}">ĐÚNG</span>
                            @if ($isTrue)
                                <span
                                    class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full border border-green-200">
                                    Đáp án được chọn
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-col items-center gap-2">
                            <div
                                class="w-16 h-16 rounded-full flex items-center justify-center
                                {{ !$isTrue ? 'bg-red-500 text-white shadow-lg ring-4 ring-red-200' : 'bg-gray-100 text-gray-400' }}">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <span
                                class="font-bold text-lg {{ !$isTrue ? 'text-red-700' : 'text-gray-400' }}">SAI</span>
                            @if (!$isTrue)
                                <span
                                    class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded-full border border-red-200">
                                    Đáp án được chọn
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- SA: Trả lời ngắn --}}
            @elseif ($typeCode === 'SA')
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                        <span
                            class="bg-blue-100 text-blue-800 rounded-full w-7 h-7 flex items-center justify-center mr-2">3</span>
                        Đáp án ngắn (SA)
                    </h3>
                    <div class="flex items-center gap-4">
                        <p class="text-sm text-gray-600">Đáp án đúng:</p>
                        <span
                            class="text-2xl font-bold tracking-widest border-2 border-blue-500 text-blue-700 rounded-lg py-2 px-6 bg-blue-50">
                            {{ $question->choices->first()?->content ?? '—' }}
                        </span>
                    </div>
                </div>

                {{-- ES: Tự luận --}}
            @elseif ($typeCode === 'ES')
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                        <span
                            class="bg-blue-100 text-blue-800 rounded-full w-7 h-7 flex items-center justify-center mr-2">3</span>
                        Câu hỏi tự luận (Essay)
                    </h3>
                    <p class="text-sm text-gray-500 italic">Câu hỏi tự luận không có đáp án cố định. Hướng dẫn chấm sẽ
                        nằm ở phần lời giải bên dưới.</p>
                </div>
            @endif

            {{-- PHẦN 4: LỜI GIẢI / HƯỚNG DẪN --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                    <span class="bg-blue-100 text-blue-800 rounded-full w-7 h-7 flex items-center justify-center mr-2">
                        {{ $typeCode === 'ES' ? '4' : '4' }}
                    </span>
                    Hướng dẫn giải / Giải thích
                </h3>
                @if ($question->explanation)
                    <div
                        class="prose max-w-none text-gray-900 bg-amber-50 rounded-lg p-4 border border-amber-100 min-h-[60px]">
                        {!! $question->explanation !!}
                    </div>
                @else
                    <p class="text-gray-400 text-sm italic">Chưa có lời giải.</p>
                @endif
            </div>

            {{-- FOOTER: NÚT HÀNH ĐỘNG --}}
            <div class="flex justify-between items-center pb-12">
                <a href="{{ route('questions.index') }}"
                    class="px-6 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Quay lại danh sách
                </a>
                <div class="flex gap-3">
                    <a href="{{ route('questions.edit', $question) }}"
                        class="px-6 py-2.5 bg-blue-700 text-white font-semibold rounded-lg hover:bg-blue-800 shadow transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                        Chỉnh sửa
                    </a>
                    <form action="{{ route('questions.destroy', $question) }}" method="POST"
                        onsubmit="return confirm('Bạn có chắc muốn xóa câu hỏi này không?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-6 py-2.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 shadow transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2" />
                            </svg>
                            Xóa câu hỏi
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
