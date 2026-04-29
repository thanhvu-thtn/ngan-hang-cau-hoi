<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nội dung chuyên đề: {{ $topicContent->name }}
            </h2>
            <a href="{{ route('topic-contents.back', ['uuid' => $uuid ?? '']) }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-600 transition">
                Quay lại
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-6 shadow sm:rounded-lg border border-gray-200">
                <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Thông tin chung</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Mã nội dung:</p>
                        <p class="font-medium text-gray-900">{{ $topicContent->code }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Thuộc Chuyên đề:</p>
                        <p class="font-medium text-gray-900">
                            [{{ $topicContent->topic->code }}] {{ $topicContent->topic->name }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Khối & Loại:</p>
                        <p class="font-medium text-gray-900">
                            {{ $topicContent->topic->grade->name }} - {{ $topicContent->topic->topicType->name }}
                        </p>
                    </div>
                </div>
            </div>
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="bg-green-50 border-l-4 border-green-500 p-4 shadow-sm rounded-r-lg mb-4 flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-bold text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="bg-red-50 border-l-4 border-red-500 p-4 shadow-sm rounded-r-lg mb-4 flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            @endif
            <div class="bg-white shadow sm:rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-700">Yêu cầu cần đạt (Objectives)</h3>
                    <div class="flex items-center space-x-3">
                        <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2.5 py-0.5 rounded-full">
                            {{ $topicContent->objectives->count() }} mục
                        </span>
                        {{-- Nút Thêm mới YCCĐ --}}
                        <a href="{{ route('objectives.create', ['topic_content_id' => $topicContent->id]) }}"
                            class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-indigo-700 transition shadow-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Thêm yêu cầu cần đạt
                        </a>
                    </div>
                </div>

                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Mã YCCĐ
                            </th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Mô tả chi
                                tiết</th>
                            {{-- Header cột Thao tác mới --}}
                            <th
                                class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center w-28">
                                Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($topicContent->objectives as $obj)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 align-top font-mono text-sm text-indigo-600 font-bold">
                                    {{ $obj->code }}
                                </td>
                                <td class="px-6 py-4 text-gray-700 leading-relaxed format-katex">
                                    {!! $obj->description !!}
                                </td>
                                {{-- Cột Thao tác với các Icon --}}
                                <td class="px-6 py-4 text-center align-top">
                                    <div class="flex justify-center space-x-3">
                                        <a href="{{ route('objectives.edit', ['objective' => $obj->id, 'topic_content_id' => $topicContent->id]) }}"
                                            class="text-blue-600 hover:text-blue-900 transition-colors"
                                            title="Chỉnh sửa">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('objectives.destroy', ['objective' => $obj->id, 'topic_content_id' => $topicContent->id]) }}" method="POST"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa yêu cầu này?')"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 transition-colors"
                                                title="Xóa">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-400 italic">
                                    Chưa có yêu cầu cần đạt nào cho nội dung này.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
