<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Chi tiết Chuyên đề: <span class="text-indigo-600">{{ $topic->name }}</span>
            </h2>
            <a href="{{ route('topics.index') }}" class="text-sm text-gray-500 hover:underline">
                &larr; Quay lại danh sách
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase">Mã chuyên đề</p>
                        <p class="text-lg font-bold text-gray-800">{{ $topic->code }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase">Khối lớp</p>
                        <p class="text-lg font-medium text-gray-800">{{ $topic->grade->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase">Phân loại</p>
                        <span class="px-2 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700">
                            {{ $topic->topicType->name }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-700">Danh sách nội dung chi tiết</h3>
                        <span class="text-sm text-gray-500">Tổng cộng: {{ $topic->contents->count() }} mục</span>
                    </div>
                    <a href="{{ route('topic-contents.create', ['topic_id' => $topic->id]) }}"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-700 transition">
                        + Thêm nội dung
                    </a>
                </div>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">STT</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Mã nội dung</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tên nội dung chi
                                tiết</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topic->contents as $index => $content)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-mono font-bold text-indigo-600">{{ $content->code }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $content->name }}</td>
                                <td class="px-6 py-4 text-sm text-center font-medium flex justify-center space-x-3">
                                    <a href="{{ route('topic-contents.edit', ['topic_content' => $content->id, 'from_topic' => $topic->id]) }}"
                                        class="text-blue-600 hover:text-blue-900">
                                        Sửa
                                    </a>
                                    <form
                                        action="{{ route('topic-contents.destroy', ['topic_content' => $content->id, 'from_topic' => $topic->id]) }}"
                                        method="POST"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa nội dung này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">
                                    Chuyên đề này hiện chưa có nội dung chi tiết nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
