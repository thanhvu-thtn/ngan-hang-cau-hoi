<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Sửa bố cục: {{ $questionLayout->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-200 p-8">
                <form action="{{ route('question-layouts.update', $questionLayout->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 gap-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Tên bố cục</label>
                                <input type="text" name="name" value="{{ old('name', $questionLayout->name) }}" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Mã (Code)</label>
                                <input type="text" name="code" value="{{ old('code', $questionLayout->code) }}" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Thứ tự hiển thị</label>
                            <input type="number" name="order_index" value="{{ old('order_index', $questionLayout->order_index) }}" class="mt-1 w-32 border-gray-300 rounded-lg shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Mô tả</label>
                            <textarea name="description" rows="3" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">{{ old('description', $questionLayout->description) }}</textarea>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('question-layouts.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg font-bold hover:bg-gray-200 transition">Quay lại</a>
                        <button type="submit" class="px-8 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 shadow-md">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>