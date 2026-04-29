<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Danh sách Yêu cầu cần đạt (Objectives)
            </h2>
            <div class="flex space-x-2">
                {{-- Nút Xuất Word mới thêm --}}

                <a href="{{ route('objectives.import.word') }}"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-emerald-700 transition">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg> Nhập từ Word
                </a>
                <a href="{{ route('objectives.create') }}"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-700 transition">
                    + Thêm mới
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
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
            </div>
            <div class="bg-white p-6 shadow sm:rounded-lg border border-gray-200">
                <form method="GET" action="{{ route('objectives.index') }}"
                    class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Mã YCCĐ</label>
                        <input type="text" name="objective_code" value="{{ request('objective_code') }}"
                            placeholder="VD: YC01..." class="w-full border-gray-300 rounded-md text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Mã Nội dung</label>
                        <input type="text" name="content_code" value="{{ request('content_code') }}"
                            placeholder="VD: ND01..." class="w-full border-gray-300 rounded-md text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Mã Chuyên đề</label>
                        <input type="text" name="topic_code" value="{{ request('topic_code') }}"
                            placeholder="VD: CD01..." class="w-full border-gray-300 rounded-md text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Khối</label>
                        <select name="grade_id" class="w-full border-gray-300 rounded-md text-sm">
                            <option value="">-- Tất cả --</option>
                            @foreach ($grades as $grade)
                                <option value="{{ $grade->id }}"
                                    {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                    {{ $grade->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Loại chuyên đề</label>
                        <select name="topic_type_id" class="w-full border-gray-300 rounded-md text-sm">
                            <option value="">-- Tất cả --</option>
                            @foreach ($topicTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ request('topic_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-5 flex justify-end gap-2 mt-2">
                        <a href="{{ route('objectives.index') }}"
                            class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm hover:bg-gray-200">Xóa lọc</a>
                        <button type="submit"
                            class="bg-indigo-600 text-white px-6 py-2 rounded-md text-sm font-bold hover:bg-indigo-700 shadow-sm">
                            Tìm kiếm & Lọc
                        </button>
                        <button type="submit" formaction="{{ route('objectives.word-export') }}"
                            class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition flex items-center shadow-sm">
                            <svg class="w-4 h-4 mr-2" ...>...</svg>
                            Xuất Word
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Mã YCCĐ</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase w-1/3">Mô tả</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Mã Nội dung</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Mã C.Đề</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase">Khối</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase">Loại</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($objectives as $obj)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-4 text-sm font-mono font-bold text-indigo-600 align-top">
                                    {{ $obj->code }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700 align-top">
                                    <div class="format-katex">
                                        {!! $obj->description !!}
                                    </div>
                                </td>
                                {{-- Tại cột Mã Nội dung --}}
                                <td class="px-4 py-4 text-sm font-mono text-gray-600">
                                    <span class="cursor-help border-b border-dotted border-gray-400"
                                        title="{{ $obj->topicContent->name }}">
                                        {{ $obj->topicContent->code }}
                                    </span>
                                </td>
                                {{-- Tại cột Mã Chuyên đề --}}
                                <td class="px-4 py-4 text-sm font-mono text-gray-600">
                                    <span class="cursor-help border-b border-dotted border-gray-400"
                                        title="{{ $obj->topicContent->topic->name }}">
                                        {{ $obj->topicContent->topic->code }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm text-center align-top">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs font-bold">
                                        {{ $obj->topicContent->topic->grade->code }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm text-center align-top">
                                    <span class="bg-gray-100 text-gray-800 px-2 py-0.5 rounded text-xs">
                                        {{ $obj->topicContent->topic->topicType->code }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center text-sm font-medium align-middle">
                                    <div class="flex justify-center space-x-3">
                                        <a href="{{ route('objectives.edit', $obj) }}"
                                            class="text-blue-600 hover:text-blue-900 transition-colors"
                                            title="Chỉnh sửa">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('objectives.destroy', $obj) }}" method="POST"
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
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400 italic">Không tìm thấy
                                    yêu cầu cần đạt nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4 border-t">
                    {{ $objectives->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
