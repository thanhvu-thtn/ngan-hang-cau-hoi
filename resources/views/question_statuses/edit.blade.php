<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Chỉnh sửa trạng thái: <span class="text-indigo-600">{{ $questionStatus->name }}</span>
            </h2>
            <a href="{{ route('question-statuses.index') }}" class="text-sm text-gray-500 hover:underline">
                &larr; Quay lại danh sách
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200">
                <form action="{{ route('question-statuses.update', $questionStatus->id) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Tên trạng thái <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $questionStatus->name) }}" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Mã định danh (Code) <span class="text-red-500">*</span></label>
                                <input type="text" name="code" value="{{ old('code', $questionStatus->code) }}" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase">
                                <p class="text-[10px] text-gray-400 mt-1 italic">Dùng để định danh trong logic code.</p>
                                @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Thứ tự sắp xếp</label>
                                <input type="number" name="order_index" value="{{ old('order_index', $questionStatus->order_index) }}" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('order_index') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Màu sắc (Tailwind Class)</label>
                                <input type="text" name="color" value="{{ old('color', $questionStatus->color) }}" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm">
                                <div class="mt-2 flex items-center space-x-2">
                                    <span class="text-[10px] text-gray-400 italic">Xem trước:</span>
                                    <span class="px-2 py-0.5 rounded-full text-xs {{ $questionStatus->color }}">
                                        {{ $questionStatus->name }}
                                    </span>
                                </div>
                                @error('color') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Mô tả chi tiết</label>
                            <textarea name="description" rows="4" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $questionStatus->description) }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t flex justify-end space-x-3">
                        <a href="{{ route('question-statuses.index') }}" 
                            class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg font-bold hover:bg-gray-200 transition">
                            Hủy
                        </a>
                        <button type="submit" 
                            class="px-8 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 shadow-md transform transition active:scale-95">
                            Cập nhật thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>