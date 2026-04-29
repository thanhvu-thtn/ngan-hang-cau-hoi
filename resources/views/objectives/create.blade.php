<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($fixedTopicContent) ? 'Thêm YCCĐ cho: ' . $fixedTopicContent->name : 'Thêm Yêu cầu cần đạt mới' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-8">
                
                <form action="{{ route('objectives.store') }}" method="POST" id="objectiveForm">
                    @csrf

                    {{-- UUID để quay lại trang trước đó sau khi lưu --}}
                    @if(isset($uuid))
                        <input type="hidden" name="uuid" value="{{ $uuid }}">
                    @endif

                    {{-- 1. PHẦN CHỌN NỘI DUNG (FILTER HOẶC FIXED) --}}
                    <div class="bg-gray-50 p-6 rounded-xl mb-8 border border-gray-200">
                        <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L14.414 7.293a1 1 0 01-.293-.707V4z" />
                            </svg>
                            Thông tin định danh
                        </h3>

                        @if(isset($fixedTopicContent))
                            {{-- Trường hợp 1: Đã xác định sẵn Nội dung chi tiết --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="p-3 bg-white rounded border border-indigo-100 shadow-sm">
                                    <label class="block text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Chuyên đề</label>
                                    <p class="text-sm font-semibold text-gray-900">{{ $fixedTopicContent->topic->name }}</p>
                                </div>
                                <div class="p-3 bg-white rounded border border-indigo-100 shadow-sm">
                                    <label class="block text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Nội dung chi tiết</label>
                                    <p class="text-sm font-semibold text-gray-900">{{ $fixedTopicContent->name }}</p>
                                </div>
                                <input type="hidden" name="topic_content_id" value="{{ $fixedTopicContent->id }}">
                            </div>
                        @else
                            {{-- Trường hợp 2: Tạo tự do (Hiện bộ lọc) --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Khối lớp</label>
                                    <select id="fGrade" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">-- Chọn khối --</option>
                                        @foreach($grades as $g) <option value="{{ $g->id }}">{{ $g->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Loại chuyên đề</label>
                                    <select id="fType" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">-- Chọn loại --</option>
                                        @foreach($topicTypes as $t) <option value="{{ $t->id }}">{{ $t->code }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Chuyên đề</label>
                                    <select id="fTopic" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">-- Chọn chuyên đề --</option>
                                        @foreach($topics as $tp) 
                                            <option value="{{ $tp->id }}" data-grade="{{ $tp->grade_id }}" data-type="{{ $tp->topic_type_id }}">{{ $tp->name }}</option> 
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-1 text-indigo-600 uppercase tracking-tighter">Nội dung chuyên đề <span class="text-red-500">*</span></label>
                                    <select name="topic_content_id" id="fContent" class="w-full border-indigo-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="">-- Chọn nội dung --</option>
                                        @foreach($contents as $c)
                                            <option value="{{ $c->id }}" data-topic="{{ $c->topic_id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- 2. PHẦN NHẬP LIỆU CHÍNH --}}
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-tighter">Mã Yêu cầu cần đạt <span class="text-red-500">*</span></label>
                            <input type="text" name="code" value="{{ old('code') }}" 
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono" 
                                   placeholder="VD: YC01" required>
                            @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-sm font-bold text-gray-700 uppercase tracking-tighter">Mô tả chi tiết <span class="text-red-500">*</span></label>
                                
                                {{-- GỌI HÀM PREVIEW TỪ COMPONENT --}}
                                <button type="button" onclick="previewContent('description')"
                                        class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 transition-all active:scale-95">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Xem trước KaTeX
                                </button>
                            </div>
                            <textarea name="description" id="description" rows="8" 
                                      class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm leading-relaxed" 
                                      placeholder="Nhập nội dung mô tả, sử dụng $...$ hoặc $$...$$ cho công thức." required>{{ old('description') }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- 3. NÚT ĐIỀU KHIỂN --}}
                    <div class="mt-10 flex items-center justify-end space-x-4 border-t pt-6">
                        <a href="{{ url()->previous() }}" class="text-sm font-bold text-gray-500 hover:text-gray-700">
                            Hủy bỏ
                        </a>
                        <button type="submit" class="bg-indigo-600 text-white px-10 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                            Lưu YCCĐ
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- GỌI COMPONENT PREVIEW MODAL --}}
    @include('components.katex.preview-modal')

    {{-- Script lọc (chỉ hiện khi tạo tự do) --}}
    @if(!isset($fixedTopicContent))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fGrade = document.getElementById('fGrade');
            const fType = document.getElementById('fType');
            const fTopic = document.getElementById('fTopic');
            const fContent = document.getElementById('fContent');

            const topicOptions = Array.from(fTopic.options);
            const contentOptions = Array.from(fContent.options);

            function filterTopics() {
                const gradeId = fGrade.value;
                const typeId = fType.value;
                fTopic.innerHTML = '<option value="">-- Chọn chuyên đề --</option>';
                topicOptions.forEach((opt, idx) => {
                    if (idx === 0) return;
                    const matchG = !gradeId || opt.dataset.grade === gradeId;
                    const matchT = !typeId || opt.dataset.type === typeId;
                    if (matchG && matchT) fTopic.appendChild(opt);
                });
                filterContents();
            }

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
            filterTopics();
        });
    </script>
    @endif
</x-app-layout>