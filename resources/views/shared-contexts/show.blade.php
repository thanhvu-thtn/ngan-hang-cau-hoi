<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dữ liệu dùng chung: <span class="text-blue-700">{{ $sharedContext->code }}</span>
            </h2>
            <a href="{{ route('shared-contexts.index') }}"
                class="text-sm text-gray-600 hover:text-gray-800 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Quay lại danh sách
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- THÔNG BÁO SUCCESS --}}
            @if (session('success'))
                <div class="flex items-center p-4 text-green-800 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm"
                    role="alert">
                    <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="ml-3 text-sm font-medium">{{ session('success') }}</div>
                    <button type="button"
                        class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg p-1.5 hover:bg-green-100 inline-flex h-8 w-8"
                        onclick="this.parentElement.remove()">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div id="flash-error"
                    class="flex items-center p-4 text-red-800 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm"
                    role="alert">
                    <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="ml-3 text-sm font-medium">{{ session('error') }}</div>
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
            {{-- ================================================================ --}}
            {{-- PHẦN 1: THÔNG TIN DỮ LIỆU DÙNG CHUNG                           --}}
            {{-- ================================================================ --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                {{-- Header card --}}
                <div class="flex items-center justify-between px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-base font-bold text-blue-800 flex items-center gap-2">
                        <span
                            class="bg-blue-100 text-blue-800 rounded-full w-7 h-7 flex items-center justify-center text-sm">1</span>
                        Thông tin dữ liệu dùng chung
                    </h3>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('shared-contexts.edit', $sharedContext) }}"
                            class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Chỉnh sửa
                        </a>
                        <button type="button"
                            onclick="document.getElementById('delete-context-modal').classList.remove('hidden')"
                            class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-bold rounded-lg transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2" />
                            </svg>
                            Xóa
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-5">
                    {{-- Mã và Mô tả --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mã dữ liệu</p>
                            <p class="mt-1 text-lg font-bold text-blue-700">{{ $sharedContext->code }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mô tả</p>
                            <p class="mt-1 text-sm text-gray-700">{{ $sharedContext->description ?: '—' }}</p>
                        </div>
                    </div>

                    {{-- Nội dung --}}
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nội dung</p>
                        <div
                            class="prose max-w-none text-gray-900 bg-gray-50 rounded-lg p-4 border border-gray-100 min-h-[80px] format-katex shared-context-content">
                            {!! $sharedContext->content !!}
                        </div>
                        <style>
                            .shared-context-content img {
                                max-width: 100% !important;
                                height: auto !important;
                                display: block;
                            }

                            .shared-context-content::after {
                                content: "";
                                display: table;
                                clear: both;
                            }

                            .shared-context-content p,
                            .shared-context-content div {
                                max-width: 100%;
                                overflow: hidden;
                            }
                        </style>
                    </div>
                </div>
            </div>

            {{-- ================================================================ --}}
            {{-- PHẦN 2: DANH SÁCH CÂU HỎI PHỤ THUỘC                           --}}
            {{-- ================================================================ --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                {{-- Header card --}}
                <div class="flex items-center justify-between px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-base font-bold text-blue-800 flex items-center gap-2">
                        <span
                            class="bg-blue-100 text-blue-800 rounded-full w-7 h-7 flex items-center justify-center text-sm">2</span>
                        Câu hỏi thuộc dữ liệu này
                        <span class="ml-1 bg-blue-600 text-white text-xs font-bold rounded-full px-2 py-0.5">
                            {{ $sharedContext->questions->count() }}
                        </span>
                    </h3>
                    @can('create-questions')
                        <a href="{{ route('questions.create', ['shared_context_id' => $sharedContext->id, 'source' => 'shared_context']) }}"
                            class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-lg transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Thêm câu hỏi
                        </a>
                    @endcan
                </div>

                {{-- Bảng câu hỏi --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Mã</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Mô tả</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Loại</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Mức độ</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Trạng thái</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($sharedContext->questions as $question)
                                <tr class="hover:bg-blue-50/30 transition-colors">

                                    {{-- Mã câu hỏi --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-700">
                                        {{ $question->code }}
                                    </td>

                                    {{-- Mô tả --}}
                                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                                        <div class="line-clamp-2" title="{{ $question->description }}">
                                            {{ $question->description ?? '—' }}
                                        </div>
                                    </td>

                                    {{-- Loại câu hỏi --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                            {{ $question->type?->code ?? 'N/A' }}
                                        </span>
                                    </td>

                                    {{-- Mức độ nhận thức --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-800 border border-orange-200">
                                            {{ $question->cognitiveLevel?->code ?? 'N/A' }}
                                        </span>
                                    </td>

                                    {{-- Trạng thái --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @php
                                            $statusColor = match ($question->status?->code) {
                                                'APPROVED' => 'bg-green-100 text-green-800 border-green-200',
                                                'PENDING' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                'REJECTED' => 'bg-red-100 text-red-800 border-red-200',
                                                'REVIEWING' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                default => 'bg-gray-100 text-gray-700 border-gray-200',
                                            };
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $statusColor }}">
                                            {{ $question->status?->code ?? 'N/A' }}
                                        </span>
                                    </td>

                                    {{-- Thao tác --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center space-x-3">

                                            {{-- Xem --}}
                                            <a href="{{ route('questions.show', [$question, 'source' => 'shared_context', 'shared_context_id' => $sharedContext->id]) }}"
                                                class="text-blue-500 hover:text-blue-700 transition"
                                                title="Xem chi tiết">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            {{-- Sửa --}}
                                            <a href="{{ route('questions.edit', [$question, 'source' => 'shared_context', 'shared_context_id' => $sharedContext->id]) }}"
                                                class="text-amber-500 hover:text-amber-700 transition"
                                                title="Chỉnh sửa">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            {{-- Xóa --}}
                                            <button type="button"
                                                onclick="confirmDeleteQuestion('{{ $question->id }}', '{{ $question->code }}')"
                                                class="text-red-400 hover:text-red-600 transition"
                                                title="Xóa câu hỏi">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2" />
                                                </svg>
                                            </button>

                                            {{-- Form xóa ẩn --}}
                                            <form id="delete-question-form-{{ $question->id }}"
                                                action="{{ route('questions.destroy', $question) }}" method="POST"
                                                class="hidden">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="source" value="shared_context">
                                                <input type="hidden" name="shared_context_id"
                                                    value="{{ $sharedContext->id }}">
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2 text-gray-400">
                                            <svg class="w-10 h-10 opacity-30" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <p class="text-sm italic">Chưa có câu hỏi nào gắn với dữ liệu dùng chung
                                                này.</p>
                                            @can('create-questions')
                                                <a href="{{ route('questions.create', ['shared_context_id' => $sharedContext->id, 'source' => 'shared_context']) }}"
                                                    class="mt-1 inline-flex items-center gap-1 text-sm text-blue-600 hover:underline font-medium">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    Thêm câu hỏi đầu tiên
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- MODAL XÁC NHẬN XÓA CÂU HỎI                                     --}}
    {{-- ================================================================ --}}
    <div id="delete-question-modal"
        class="hidden fixed inset-0 z-50 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 border border-gray-200 overflow-hidden">
            <div class="p-6 flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Xác nhận xóa câu hỏi</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Bạn có chắc muốn xóa câu hỏi
                        <span id="delete-question-code-label" class="font-bold text-red-600"></span>?
                        Hành động này không thể hoàn tác.
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <button type="button"
                    onclick="document.getElementById('delete-question-modal').classList.add('hidden')"
                    class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium text-sm">
                    Hủy bỏ
                </button>
                <button type="button" id="delete-question-confirm-btn"
                    class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-bold text-sm shadow-sm">
                    Xóa
                </button>
            </div>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- MODAL XÁC NHẬN XÓA SHARED CONTEXT                              --}}
    {{-- ================================================================ --}}
    <div id="delete-context-modal"
        class="hidden fixed inset-0 z-50 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 border border-gray-200 overflow-hidden">
            <div class="p-6 flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Xác nhận xóa dữ liệu dùng chung</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Bạn có chắc muốn xóa <span class="font-bold text-red-600">{{ $sharedContext->code }}</span>?
                        @if ($sharedContext->questions->count() > 0)
                            <span class="block mt-1 text-amber-700 font-semibold">
                                ⚠ Hiện có {{ $sharedContext->questions->count() }} câu hỏi đang gắn với dữ liệu này.
                            </span>
                        @endif
                        Hành động này không thể hoàn tác.
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <button type="button"
                    onclick="document.getElementById('delete-context-modal').classList.add('hidden')"
                    class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium text-sm">
                    Hủy bỏ
                </button>
                <form action="{{ route('shared-contexts.destroy', $sharedContext) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-bold text-sm shadow-sm">
                        Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>

<script>
    function confirmDeleteQuestion(id, code) {
        document.getElementById('delete-question-code-label').textContent = code;
        document.getElementById('delete-question-confirm-btn').onclick = function() {
            document.getElementById('delete-question-form-' + id).submit();
        };
        document.getElementById('delete-question-modal').classList.remove('hidden');
    }

    // Đóng modal khi click backdrop
    ['delete-question-modal', 'delete-context-modal'].forEach(function(id) {
        document.getElementById(id).addEventListener('click', function(e) {
            if (e.target === this) this.classList.add('hidden');
        });
    });
</script>
