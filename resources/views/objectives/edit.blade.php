<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Chỉnh sửa Yêu cầu cần đạt: <span class="text-indigo-600">{{ $objective->code }}</span>
            </h2>
            <a href="{{ route('objectives.index') }}" class="text-sm text-gray-500 hover:underline">
                &larr; Quay lại danh sách
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <form action="{{ route('objectives.update', $objective->id) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')
                    {{-- THÊM DÒNG NÀY: Gửi UUID lên controller để xử lý quay về trang cũ --}}
                    @if (isset($backUuid))
                        <input type="hidden" name="uuid" value="{{ $backUuid }}">
                    @endif
                    <div
                        class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Khối lớp</label>
                            <input type="text" disabled value="{{ $objective->topicContent->topic->grade->code }}"
                                class="w-full bg-gray-100 border-gray-200 rounded-md text-sm text-gray-600 font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Loại chuyên đề</label>
                            <input type="text" disabled
                                value="{{ $objective->topicContent->topic->topicType->code }}"
                                class="w-full bg-gray-100 border-gray-200 rounded-md text-sm text-gray-600 font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Tên Chuyên đề</label>
                            <input type="text" disabled value="{{ $objective->topicContent->topic->name }}"
                                class="w-full bg-gray-100 border-gray-200 rounded-md text-sm text-gray-600">
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="topic_content_id" class="block text-sm font-bold text-gray-700 mb-2">
                                Thuộc Nội dung chuyên đề <span class="text-red-500">*</span>
                            </label>
                            <select name="topic_content_id" id="topic_content_id" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach ($topicContents as $content)
                                    <option value="{{ $content->id }}"
                                        {{ $objective->topic_content_id == $content->id ? 'selected' : '' }}>
                                        [{{ $content->code }}] {{ $content->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('topic_content_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-bold text-gray-700 mb-2">
                                Mã định danh YCCĐ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="code" id="code"
                                value="{{ old('code', $objective->code) }}" required placeholder="VD: YC01"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono">
                            @error('code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label for="description" class="block text-sm font-bold text-gray-700">
                                    Mô tả yêu cầu cần đạt <span class="text-red-500">*</span>
                                </label>

                                <button type="button" onclick="previewContent('description')"
                                    class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded hover:bg-amber-200 transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Xem trước (KaTeX)
                                </button>
                            </div>

                            <textarea name="description" id="description" rows="6" required
                                placeholder="Nhập nội dung yêu cầu... Có thể dùng $ công thức $ để viết toán học."
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $objective->description) }}</textarea>
                            <p class="text-xs text-gray-400 mt-2 italic">* Mẹo: Sử dụng $...$ cho công thức toán học
                                inline, $$...$$ cho công thức đứng riêng dòng.</p>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-10 pt-6 border-t flex justify-end">
                        <button type="submit"
                            class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-indigo-700 shadow-lg transform transition active:scale-95">
                            Cập nhật thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('components.katex.preview-modal')

</x-app-layout>
