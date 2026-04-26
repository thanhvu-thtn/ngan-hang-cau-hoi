<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Thêm cấu hình mới</h2>
    </x-slot>

    <div class="py-12 max-w-2xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form action="{{ route('system_settings.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="key" class="block text-sm font-medium text-gray-700">Từ khóa (Key) VD: school_name</label>
                    <input type="text" name="key" id="key" value="{{ old('key') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    @error('key') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="value" class="block text-sm font-medium text-gray-700">Giá trị (Value)</label>
                    <textarea name="value" id="value" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('value') }}</textarea>
                </div>

                <div class="flex justify-end mt-6">
                    <a href="{{ route('system_settings.index') }}" class="mr-4 text-gray-600 hover:text-gray-900 mt-2">Hủy</a>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>