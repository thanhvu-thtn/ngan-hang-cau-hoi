<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Quản lý Chuyên đề
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('topics.import.form') }}"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-emerald-700 transition shadow-sm flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Import Excel
                </a>
                <a href="{{ route('topics.create') }}"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-700 transition shadow-sm flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Thêm mới
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-5 mb-6 shadow-sm sm:rounded-xl border border-gray-200">
                <form action="{{ route('topics.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">Mã chuyên
                            đề</label>
                        <input type="text" name="code" value="{{ request('code') }}" placeholder="VD: 10-1CB..."
                            class="w-full px-4 border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <div class="w-48">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">Khối
                            lớp</label>
                        <select name="grade_id"
                            class="w-full px-4 border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Tất cả</option>
                            @foreach ($grades as $grade)
                                <option value="{{ $grade->id }}"
                                    {{ request('grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-48">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">Loại
                            chuyên đề</label>
                        <select name="topic_type_id"
                            class="w-full px-4 border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Tất cả</option>
                            @foreach ($topicTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ request('topic_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-bold hover:bg-black transition">
                            Lọc
                        </button>

                        <a href="{{ route('topics.export', request()->all()) }}"
                            class="bg-emerald-600 text-white px-5 py-2 rounded-lg text-sm font-bold hover:bg-emerald-700 transition flex items-center shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Xuất Excel
                        </a>

                        @if (request()->anyFilled(['code', 'grade_id', 'topic_type_id']))
                            <a href="{{ route('topics.index') }}"
                                class="bg-gray-100 text-gray-600 px-5 py-2 rounded-lg text-sm font-bold hover:bg-gray-200 transition">
                                Xóa lọc
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                                Mã số</th>
                            <th
                                class="px-6 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                                Tên chuyên đề</th>
                            <th
                                class="px-6 py-3 text-center text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                                Khối</th>
                            <th
                                class="px-6 py-3 text-center text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                                Phân loại</th>
                            <th
                                class="px-6 py-3 text-right text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                                Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($topics as $topic)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-2.5 whitespace-nowrap">
                                    <span
                                        class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">
                                        {{ $topic->code }}
                                    </span>
                                </td>
                                <td class="px-6 py-2.5">
                                    <div class="text-sm font-semibold text-gray-900">{{ $topic->name }}</div>
                                </td>
                                <td class="px-6 py-2.5 text-center">
                                    <span
                                        class="text-sm text-gray-600 font-medium">{{ $topic->grade->name ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-2.5 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ $topic->topicType->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-2.5 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('topics.edit', $topic) }}"
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                            title="Chỉnh sửa">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('topics.destroy', $topic) }}" method="POST"
                                            onsubmit="return confirm('Bạn chắc chắn muốn xóa chuyên đề này?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition"
                                                title="Xóa bỏ">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 italic">
                                    Không có dữ liệu chuyên đề nào được tìm thấy.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $topics->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
