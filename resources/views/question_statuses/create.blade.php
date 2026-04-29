<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Thêm Trạng thái mới</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-8 border border-gray-200">
                <form action="{{ route('question-statuses.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Tên trạng thái</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Mã (Code)</label>
                                <input type="text" name="code" value="{{ old('code') }}" placeholder="VD: PENDING" class="mt-1 w-full border-gray-300 rounded-md shadow-sm uppercase">
                                @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Thứ tự</label>
                                <input type="number" name="order_index" value="{{ old('order_index', 0) }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Lớp màu sắc (Tailwind)</label>
                                <input type="text" name="color" value="{{ old('color', 'bg-gray-100 text-gray-800') }}" placeholder="bg-blue-100 text-blue-800" class="mt-1 w-full border-gray-300 rounded-md shadow-sm font-mono text-sm">
                                <p class="text-[10px] text-gray-400 mt-1 italic text-wrap">VD: bg-green-100 text-green-800, bg-red-100 text-red-800...</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700">Mô tả</label>
                            <textarea name="description" rows="3" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('question-statuses.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md font-bold">Hủy</a>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md font-bold hover:bg-indigo-700">Lưu lại</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>