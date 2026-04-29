<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Thêm Bố cục mới</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-200 p-8">
                <form action="{{ route('question-layouts.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Tên bố cục <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="VD: 2 Hàng x 2 Cột" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Mã (Code) <span class="text-red-500">*</span></label>
                                <input type="text" name="code" value="{{ old('code') }}" placeholder="VD: 2x2" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
                                @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Thứ tự hiển thị</label>
                            <input type="number" name="order_index" value="{{ old('order_index', 0) }}" class="mt-1 w-32 border-gray-300 rounded-lg shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Mô tả</label>
                            <textarea name="description" rows="3" placeholder="Dùng khi các lựa chọn có độ dài trung bình..." class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('question-layouts.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg font-bold hover:bg-gray-200 transition">Hủy</a>
                        <button type="submit" class="px-8 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 shadow-md">Lưu bố cục</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>