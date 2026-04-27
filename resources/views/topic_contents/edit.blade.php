<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Chỉnh sửa Nội dung: <span class="text-indigo-600">{{ $topicContent->code }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="p-8">
                    <form action="{{ route('topic-contents.update', $topicContent) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="edit_uuid" value="{{ $editUuid ?? '' }}">
                        <div class="bg-indigo-50/50 p-5 rounded-xl mb-8 border border-indigo-100">
                            <h3
                                class="text-sm font-bold text-indigo-900 mb-4 uppercase tracking-wider flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                    </path>
                                </svg>
                                Lọc & Chọn Chuyên đề
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="filter_grade"
                                        class="block text-[11px] font-bold text-gray-500 uppercase mb-1">1. Lọc theo Mã
                                        Khối</label>
                                    <select id="filter_grade"
                                        class="w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <option value="">-- Tất cả mã khối --</option>
                                        @foreach ($grades as $grade)
                                            <option value="{{ $grade->id }}"
                                                {{ $topicContent->topic->grade_id == $grade->id ? 'selected' : '' }}>
                                                {{ $grade->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="filter_type"
                                        class="block text-[11px] font-bold text-gray-500 uppercase mb-1">2. Lọc theo
                                        Loại CĐ</label>
                                    <select id="filter_type"
                                        class="w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <option value="">-- Tất cả loại CĐ --</option>
                                        @foreach ($topicTypes as $type)
                                            <option value="{{ $type->id }}"
                                                {{ $topicContent->topic->topic_type_id == $type->id ? 'selected' : '' }}>
                                                {{ $type->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="topic_id" class="block text-sm font-bold text-gray-800 mb-2">3. Chọn Tên
                                    Chuyên đề <span class="text-red-500">*</span></label>
                                <select name="topic_id" id="topic_id"
                                    class="w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm bg-white"
                                    required>
                                    <option value="">-- Vui lòng chọn chuyên đề --</option>
                                    @foreach ($topics as $topic)
                                        <option value="{{ $topic->id }}" data-grade="{{ $topic->grade_id }}"
                                            data-type="{{ $topic->topic_type_id }}"
                                            {{ old('topic_id', $topicContent->topic_id) == $topic->id ? 'selected' : '' }}>
                                            [{{ $topic->code }}] {{ $topic->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('topic_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label for="code" class="block text-sm font-bold text-gray-700 mb-2">Mã định danh
                                    nội dung <span class="text-red-500">*</span></label>
                                <input type="text" name="code" id="code"
                                    value="{{ old('code', $topicContent->code) }}" placeholder="VD: ND01"
                                    class="w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                    required>
                                @error('code')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Tên nội dung
                                    chi tiết <span class="text-red-500">*</span></label>
                                <textarea name="name" id="name" rows="3" placeholder="Nhập tên nội dung đầy đủ tại đây..."
                                    class="w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                    required>{{ old('name', $topicContent->name) }}</textarea>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end items-center mt-8 pt-6 border-t border-gray-100 gap-3">
                            <a href="{{ route('topic-contents.index') }}"
                                class="text-sm font-semibold text-gray-600 hover:underline">Hủy bỏ</a>
                            <button type="submit"
                                class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700 transition shadow-md">
                                Cập nhật thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const filterGrade = document.getElementById('filter_grade');
            const filterType = document.getElementById('filter_type');
            const topicSelect = document.getElementById('topic_id');
            const topicOptions = Array.from(topicSelect.options);

            function filterTopics() {
                const selectedGrade = filterGrade.value;
                const selectedType = filterType.value;
                const currentTopicVal = topicSelect.value;
                let isCurrentValStillValid = false;

                topicSelect.innerHTML = '';
                topicSelect.appendChild(topicOptions[0]);

                topicOptions.forEach((option, index) => {
                    if (index === 0) return;

                    const gradeId = option.getAttribute('data-grade');
                    const typeId = option.getAttribute('data-type');

                    const matchGrade = !selectedGrade || gradeId === selectedGrade;
                    const matchType = !selectedType || typeId === selectedType;

                    if (matchGrade && matchType) {
                        topicSelect.appendChild(option);
                        if (option.value === currentTopicVal) {
                            isCurrentValStillValid = true;
                        }
                    }
                });

                // Giữ lại giá trị đang chọn nếu nó vẫn nằm trong danh sách sau khi lọc
                topicSelect.value = isCurrentValStillValid ? currentTopicVal : '';
            }

            filterGrade.addEventListener('change', filterTopics);
            filterType.addEventListener('change', filterTopics);

            // Tự động chạy lọc ngay khi load trang để đồng bộ với bộ lọc Khối/Loại đã chọn ở trên
            filterTopics();
        });
    </script>
</x-app-layout>
