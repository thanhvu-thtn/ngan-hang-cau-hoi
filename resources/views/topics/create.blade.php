<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Thêm chuyên đề mới</h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
            <form action="{{ route('topics.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Tên chuyên đề</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="VD: Đạo hàm hàm số hợp" required>
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Mã chuyên đề (Code)</label>
                        <input type="text" name="code" value="{{ old('code') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="VD: TOAN12-CD1" required>
                    </div>

                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Khối lớp</label>
                        <select name="grade_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Kiểu chuyên đề</label>
                        <select name="topic_type_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @foreach($topicTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end mt-8 border-t pt-4">
                    <a href="{{ route('topics.index') }}" class="mr-4 text-sm text-gray-600 mt-2 hover:underline">Hủy</a>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 shadow-sm font-bold">Lưu chuyên đề</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>