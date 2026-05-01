<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Danh sách câu hỏi') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- THÔNG BÁO SUCCESS --}}
            @if (session('success'))
                <div class="mb-6 flex items-center p-4 text-green-800 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm"
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
            {{-- Đặt ngay cạnh block @if (session('success')) --}}
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

            {{-- Div để hiện lỗi từ JS (ẩn mặc định) --}}
            <div id="client-error-banner"
                class="hidden flex items-center p-4 text-red-800 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm"
                role="alert">
                <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <div id="client-error-message" class="ml-3 text-sm font-medium"></div>
                <button type="button"
                    class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg p-1.5 hover:bg-red-100 inline-flex h-8 w-8"
                    onclick="this.parentElement.classList.add('hidden')">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            {{-- THÔNG BÁO LỖI --}}
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- =============================================================== --}}
                    {{-- BỘ LỌC & NÚT THÊM MỚI                                         --}}
                    {{-- =============================================================== --}}
                    <form id="filter-form" action="{{ route('questions.index') }}" method="GET">

                        <div
                            class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 border-b border-gray-100 pb-6">

                            {{-- Vùng trái: Các bộ lọc --}}
                            <div class="flex flex-wrap items-center gap-2 flex-1 w-full md:w-auto">

                                {{-- Tìm kiếm mã câu hỏi --}}
                                <div class="relative w-full md:w-64">
                                    <span
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 pointer-events-none">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </span>
                                    <input type="text" name="search_code" value="{{ request('search_code') }}"
                                        placeholder="Tìm mã câu hỏi..."
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition shadow-sm">
                                </div>

                                {{-- Nút lọc theo Mục tiêu --}}
                                <button type="button"
                                    onclick="document.getElementById('filter-modal').classList.remove('hidden')"
                                    class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition border border-gray-300 shadow-sm text-sm">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                                    </svg>
                                    Mục tiêu
                                    @if (count(request('objective_ids', [])) > 0)
                                        <span
                                            class="ml-2 bg-blue-600 text-white text-[10px] rounded-full px-1.5 py-0.5 font-bold">
                                            {{ count(request('objective_ids')) }}
                                        </span>
                                    @endif
                                </button>

                                {{-- Nút Tìm kiếm --}}
                                <button type="submit"
                                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                                    Tìm kiếm
                                </button>

                                {{-- Nút xóa bộ lọc --}}
                                @if (request()->has('search_code') || request()->has('objective_ids'))
                                    <a href="{{ route('questions.index') }}"
                                        class="p-2 text-gray-400 hover:text-red-600 transition"
                                        title="Xóa tất cả bộ lọc">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </a>
                                @endif
                            </div>

                            {{-- Vùng phải: Nút Thêm mới --}}
                            <div class="flex-shrink-0 w-full md:w-auto text-right">
                                <a href="{{ route('questions.create') }}"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-lg transition shadow-md">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Thêm mới
                                </a>
                            </div>
                        </div>

                        {{-- =============================================================== --}}
                        {{-- MODAL LỌC THEO MỤC TIÊU (TREEVIEW)                            --}}
                        {{-- =============================================================== --}}
                        <div id="filter-modal"
                            class="hidden fixed inset-0 z-50 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center overflow-hidden">
                            <div
                                class="bg-white rounded-xl shadow-2xl w-[95vw] h-[95vh] flex flex-col overflow-hidden border border-gray-200">

                                {{-- Header --}}
                                <div
                                    class="px-6 py-4 border-b border-gray-200 bg-white flex justify-between items-center flex-shrink-0">
                                    <h3 class="text-xl font-bold text-gray-900">
                                        Lọc theo Yêu cầu cần đạt (Objectives)
                                    </h3>
                                    <button type="button"
                                        onclick="document.getElementById('filter-modal').classList.add('hidden')"
                                        class="text-gray-400 hover:text-red-500 hover:bg-red-50 p-2 rounded-lg transition">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- Body --}}
                                <div class="flex-grow overflow-y-auto p-6 bg-gray-50 text-gray-900">
                                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 h-full">
                                        <x-treeview.objective-selector :items="$treeData" :selected="request('objective_ids', [])" />
                                    </div>
                                </div>

                                {{-- Footer --}}
                                <div
                                    class="px-6 py-4 bg-white border-t border-gray-200 flex items-center justify-end space-x-3 flex-shrink-0">
                                    <button type="button"
                                        onclick="document.getElementById('filter-modal').classList.add('hidden')"
                                        class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-100 transition shadow-sm">
                                        Hủy bỏ
                                    </button>
                                    <button type="submit"
                                        class="px-8 py-2.5 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition shadow-md">
                                        Áp dụng lọc
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>

                    {{-- =============================================================== --}}
                    {{-- BẢNG DANH SÁCH CÂU HỎI                                        --}}
                    {{-- =============================================================== --}}
                    <div class="overflow-x-auto border border-gray-200 rounded-xl shadow-sm">
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
                                @forelse ($questions as $question)
                                    <tr class="hover:bg-blue-50/30 transition-colors">

                                        {{-- Mã câu hỏi --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-700">
                                            {{ $question->code }}
                                            @if ($question->sharedContext)
                                                <div class="mt-0.5">
                                                    <a href="{{ route('shared-contexts.show', $question->sharedContext) }}"
                                                        class="inline-flex items-center gap-0.5 text-[10px] font-medium text-indigo-500 hover:text-indigo-700 hover:underline transition"
                                                        title="Xem dữ liệu dùng chung: {{ $question->sharedContext->code }}">
                                                        <svg class="w-2.5 h-2.5 flex-shrink-0" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                        </svg>
                                                        có dữ liệu dùng chung
                                                    </a>
                                                </div>
                                            @endif
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
                                                {{ $question->type->code ?? 'N/A' }}
                                            </span>
                                        </td>

                                        {{-- Mức độ nhận thức --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-800 border border-orange-200">
                                                {{ $question->cognitiveLevel->code ?? 'N/A' }}
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
                                                <a href="{{ route('questions.show', $question) }}"
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
                                                <a href="{{ route('questions.edit', $question) }}"
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
                                                    onclick="confirmDeleteQuestion('{{ $question->id }}', '{{ $question->code }}', {{ $question->shared_context_id ? 'true' : 'false' }})"
                                                    class="text-red-400 hover:text-red-600 transition"
                                                    title="Xóa câu hỏi">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>

                                                {{-- Form xóa ẩn --}}
                                                <form id="delete-form-{{ $question->id }}"
                                                    action="{{ route('questions.destroy', $question) }}"
                                                    method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="source" value="index">
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">
                                            Không tìm thấy câu hỏi nào.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Phân trang --}}
                    <div class="mt-6">
                        {{ $questions->links() }}
                    </div>

                </div>
            </div>

        </div>

        {{-- =============================================================== --}}
        {{-- MODAL XÁC NHẬN XÓA                                             --}}
        {{-- =============================================================== --}}
        <div id="delete-modal"
            class="hidden fixed inset-0 z-50 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 border border-gray-200 overflow-hidden">

                {{-- Header --}}
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
                            <span id="delete-question-code" class="font-bold text-red-600"></span>?
                            Hành động này không thể hoàn tác.
                        </p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                        class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium text-sm">
                        Hủy bỏ
                    </button>
                    <button type="button" id="delete-confirm-btn"
                        class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-bold text-sm shadow-sm">
                        Xóa
                    </button>
                </div>
            </div>
        </div>

</x-app-layout>

<script>
    /**
     * Hiển thị modal xác nhận xóa và gắn form tương ứng.
     */
    // Đóng modal khi click ra ngoài backdrop
    document.getElementById('delete-modal').addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });

    function confirmDeleteQuestion(id, code, hasSharedContext) {
        if (hasSharedContext) {
            const banner = document.getElementById('client-error-banner');
            const message = document.getElementById('client-error-message');
            message.textContent = `Câu hỏi "${code}" thuộc về một dữ liệu dùng chung nên không thể xoá ở đây.`;
            banner.classList.remove('hidden');
            banner.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            return;
        }

        // Dùng đúng tên element của index.blade.php
        document.getElementById('delete-question-code').textContent = code;
        document.getElementById('delete-confirm-btn').onclick = function() {
            document.getElementById('delete-form-' + id).submit();
        };
        document.getElementById('delete-modal').classList.remove('hidden');
    }
</script>
