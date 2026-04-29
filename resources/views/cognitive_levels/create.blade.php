<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Thêm Mức độ nhận thức mới
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <form action="{{ route('cognitive-levels.store') }}" method="POST" class="p-8">
                    @csrf

                    <div class="grid grid-cols-1 gap-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Tên mức độ <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="VD: Nhận biết"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Mã định danh (Code) <span class="text-red-500">*</span></label>
                                <input type="text" name="code" value="{{ old('code') }}" placeholder="VD: NB"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase">
                                @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Thứ tự sắp xếp <span class="text-red-500">*</span></label>
                            <input type="number" name="order_index" value="{{ old('order_index', 0) }}"
                                class="w-32 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-xs text-gray-400 mt-1 italic">Dùng để sắp xếp mức độ từ dễ đến khó (1, 2, 3...)</p>
                            @error('order_index') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Mô tả chi tiết</label>
                            <textarea name="description" rows="4" placeholder="Mô tả về đặc điểm của mức độ này..."
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t flex justify-end space-x-3">
                        <a href="{{ route('cognitive-levels.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg font-bold hover:bg-gray-200 transition">Hủy</a>
                        <button type="submit" class="px-8 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 shadow-md">Lưu thông tin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>