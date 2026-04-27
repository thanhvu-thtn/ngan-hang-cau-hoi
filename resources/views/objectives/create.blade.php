<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Thêm Yêu cầu cần đạt (Objective) mới
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="p-8">
                    <form action="{{ route('objectives.store') }}" method="POST">
                        @csrf

                        <div class="bg-gray-50 p-6 rounded-xl mb-8 border border-gray-200">
                            <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                Bước 1: Lọc để chọn Nội dung chuyên đề
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="filter_grade" class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Mã Khối</label>
                                    <select id="filter_grade" class="w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <option value="">-- Tất cả khối --</option>
                                        @foreach($grades as $grade)
                                            <option value="{{ $grade->id }}">{{ $grade->code }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="filter_type" class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Loại Chuyên đề</label>
                                    <select id="filter_type" class="w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <option value="">-- Tất cả loại --</option>
                                        @foreach($topicTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="filter_topic" class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Tên Chuyên đề</label>
                                <select id="filter_topic" class="w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm bg-white">
                                    <option value="">-- Chọn chuyên đề --</option>
                                    @foreach($topics as $topic)
                                        <option value="{{ $topic->id }}" data-grade="{{ $topic->grade_id }}" data-type="{{ $topic->topic_type_id }}">
                                            {{ $topic->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="topic_content_id" class="block text-sm font-bold text-gray-800 mb-2">Thuộc Nội dung chuyên đề <span class="text-red-500">*</span></label>
                                <select name="topic_content_id" id="topic_content_id" class="w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-semibold bg-white" required>
                                    <option value="">-- Chọn nội dung --</option>
                                    @foreach($topicContents as $content)
                                        <option value="{{ $content->id }}" data-topic="{{ $content->topic_id }}">
                                            {{ $content->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('topic_content_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="space-y-6">
                            <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Bước 2: Thông tin Yêu cầu cần đạt
                            </h3>

                            <div>
                                <label for="code" class="block text-sm font-bold text-gray-700 mb-2">Mã định danh YCCĐ <span class="text-red-500">*</span></label>
                                <input type="text" name="code" id="code" value="{{ old('code') }}" placeholder="VD: YC-11-01"
                                       class="w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label for="description" class="block text-sm font-bold text-gray-700">Mô tả Yêu cầu cần đạt <span class="text-red-500">*</span></label>
                                    <button type="button" onclick="previewContent('description')"
                                            class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-700 border border-amber-200 rounded-md text-xs font-bold hover:bg-amber-200 transition shadow-sm">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Xem trước KaTeX
                                    </button>
                                </div>
                                <textarea name="description" id="description" rows="6" placeholder="Nhập mô tả chi tiết yêu cầu, bạn có thể gõ công thức toán $...$ hoặc $$...$$"
                                       class="w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>{{ old('description') }}</textarea>
                                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end items-center mt-8 pt-6 border-t border-gray-100 gap-3">
                            <a href="{{ route('objectives.index') }}" class="text-sm font-semibold text-gray-600 hover:underline">Hủy bỏ</a>
                            <button type="submit" class="bg-indigo-600 text-white px-8 py-2.5 rounded-lg font-bold hover:bg-indigo-700 transition shadow-lg">
                                Lưu Yêu cầu cần đạt
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('components.katex.preview-modal')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fGrade = document.getElementById('filter_grade');
            const fType = document.getElementById('filter_type');
            const fTopic = document.getElementById('filter_topic');
            const fContent = document.getElementById('topic_content_id');

            const topicOptions = Array.from(fTopic.options);
            const contentOptions = Array.from(fContent.options);

            // Hàm lọc Chuyên đề dựa trên Khối và Loại
            function filterTopics() {
                const grade = fGrade.value;
                const type = fType.value;

                fTopic.innerHTML = '<option value="">-- Chọn chuyên đề --</option>';
                topicOptions.forEach((opt, idx) => {
                    if (idx === 0) return;
                    const matchG = !grade || opt.dataset.grade === grade;
                    const matchT = !type || opt.dataset.type === type;
                    if (matchG && matchT) fTopic.appendChild(opt);
                });
                filterContents(); // Cập nhật lại list nội dung
            }

            // Hàm lọc Nội dung chuyên đề dựa trên Chuyên đề đã chọn
            function filterContents() {
                const topicId = fTopic.value;
                fContent.innerHTML = '<option value="">-- Chọn nội dung --</option>';
                contentOptions.forEach((opt, idx) => {
                    if (idx === 0) return;
                    if (!topicId || opt.dataset.topic === topicId) fContent.appendChild(opt);
                });
            }

            fGrade.addEventListener('change', filterTopics);
            fType.addEventListener('change', filterTopics);
            fTopic.addEventListener('change', filterContents);

            // Khởi động lọc lần đầu
            filterTopics();
        });
    </script>
</x-app-layout>