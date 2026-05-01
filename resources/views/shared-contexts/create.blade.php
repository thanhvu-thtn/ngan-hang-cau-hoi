<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('shared-contexts.index') }}"
                class="text-gray-400 hover:text-gray-600 transition"
                title="Quay lại danh sách">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Thêm dữ liệu dùng chung mới') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

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
                        <span class="text-sm font-bold">Vui lòng kiểm tra lại các trường sau:</span>
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

            <form action="{{ route('shared-contexts.store') }}" method="POST">
                @csrf

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 space-y-6">

                        {{-- ===== MÃ DỮ LIỆU DÙNG CHUNG ===== --}}
                        <div>
                            <label for="code"
                                class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Mã dữ liệu dùng chung
                                <span class="text-red-500 ml-0.5">*</span>
                            </label>
                            <input type="text"
                                id="code"
                                name="code"
                                value="{{ old('code') }}"
                                placeholder="Ví dụ: SC_VAN7_001"
                                maxlength="50"
                                class="block w-full md:w-72 px-3 py-2 border rounded-lg text-sm font-mono tracking-wide shadow-sm transition
                                    focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                    {{ $errors->has('code') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            @error('code')
                                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-400">Mã không được trùng lặp, tối đa 50 ký tự.</p>
                        </div>

                        {{-- ===== MÔ TẢ ===== --}}
                        <div>
                            <label for="description"
                                class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Mô tả
                            </label>
                            <input type="text"
                                id="description"
                                name="description"
                                value="{{ old('description') }}"
                                placeholder="Mô tả ngắn gọn về ngữ cảnh này..."
                                maxlength="255"
                                class="block w-full px-3 py-2 border rounded-lg text-sm shadow-sm transition
                                    focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                    {{ $errors->has('description') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            @error('description')
                                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-400">Không bắt buộc. Tối đa 255 ký tự.</p>
                        </div>

                        {{-- ===== NỘI DUNG (CONTENT) ===== --}}
                        <div>
                            <label for="content"
                                class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Nội dung dữ liệu dùng chung
                                <span class="text-red-500 ml-0.5">*</span>
                            </label>
                            <p class="text-xs text-gray-400 mb-2">
                                Nhập đoạn văn bản, bảng số liệu, đoạn thơ... mà các câu hỏi liên quan sẽ dùng chung.
                            </p>
                            <div class="mb-2 flex space-x-2">
                                <button type="button" onclick="initTinyMCE('content')"
                                    class="text-xs px-2 py-1 bg-gray-200 rounded">Bật soạn thảo</button>
                                <button type="button" onclick="tinymce.execCommand('mceRemoveEditor', false, 'content')"
                                    class="text-xs px-2 py-1 bg-red-50 text-red-600 rounded">Tắt</button>
                                <button type="button" onclick="showPreview('content')"
                                    class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded font-bold">Xem trước</button>
                            </div>
                            <textarea
                                id="content"
                                name="content"
                                rows="12"
                                class="block w-full px-3 py-2 border rounded-lg text-sm shadow-sm transition resize-y
                                    focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                    {{ $errors->has('content') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    {{-- ===== FOOTER: NÚT HÀNH ĐỘNG ===== --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3">
                        <a href="{{ route('shared-contexts.index') }}"
                            class="px-5 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-100 transition">
                            Hủy bỏ
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-lg transition shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Lưu dữ liệu dùng chung
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    {{-- COMPONENT EDITOR --}}
    @include('components.tinymce.editor')

</x-app-layout>
