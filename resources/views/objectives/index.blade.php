<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Danh sách Yêu cầu cần đạt (Objectives)
            </h2>
            <div class="flex space-x-2">
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
                                    {{ $grade->name }}
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
                                    {{ $type->name }}
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
                                <td class="px-4 py-4 text-sm text-gray-600 align-top">{{ $obj->topicContent->code }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600 align-top">
                                    {{ $obj->topicContent->topic->code }}</td>
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
                                <td class="px-4 py-4 text-right text-sm font-medium align-top">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('objectives.edit', $obj) }}"
                                            class="text-indigo-600 hover:text-indigo-900">Sửa</a>
                                        <form action="{{ route('objectives.destroy', $obj) }}" method="POST"
                                            onsubmit="return confirm('Xóa yêu cầu này?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900">Xóa</button>
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
