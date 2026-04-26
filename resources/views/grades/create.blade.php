<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Thêm khối lớp mới</h2>
    </x-slot>

    <div class="py-12 max-w-2xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form action="{{ route('grades.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Tên khối lớp (VD: Khối 10)</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="code" class="block text-sm font-medium text-gray-700">Mã nhận diện (Code) (VD: K10)</label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end mt-6">
                    <a href="{{ route('grades.index') }}" class="mr-4 text-gray-600 hover:text-gray-900 mt-2">Hủy</a>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>