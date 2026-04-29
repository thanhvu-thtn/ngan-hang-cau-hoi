<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Quản lý Trạng thái câu hỏi</h2>
            <a href="{{ route('question-statuses.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-indigo-700">+ Thêm trạng thái</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 border-l-4 border-green-500">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Thứ tự</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Trạng thái</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Mã (Code)</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Mô tả</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($statuses as $status)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm">{{ $status->order_index }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $status->color ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $status->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-mono text-indigo-600">{{ $status->code }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $status->description }}</td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('question-statuses.edit', $status->id) }}" class="text-indigo-600 hover:text-indigo-900">Sửa</a>
                                    <form action="{{ route('question-statuses.destroy', $status->id) }}" method="POST" onsubmit="return confirm('Xóa trạng thái này?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>