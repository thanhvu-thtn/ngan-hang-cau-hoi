<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Quản lý Bố cục hiển thị</h2>
            <a href="{{ route('question-layouts.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-indigo-700 transition">+ Thêm bố cục</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Thứ tự</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Mã (Code)</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tên bố cục</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Mô tả</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($layouts as $layout)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $layout->order_index }}</td>
                            <td class="px-6 py-4 text-sm font-mono text-indigo-600">{{ $layout->code }}</td>
                            <td class="px-6 py-4 text-sm font-bold">{{ $layout->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($layout->description, 70) }}</td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('question-layouts.edit', $layout->id) }}" class="text-indigo-600 hover:text-indigo-900">Sửa</a>
                                    <form action="{{ route('question-layouts.destroy', $layout->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bố cục này?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Chưa có dữ liệu bố cục.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>