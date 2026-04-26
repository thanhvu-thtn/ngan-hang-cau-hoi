<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Thêm kiểu chuyên đề mới
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('topic-types.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Tên kiểu chuyên đề (VD: Cơ bản, Nâng cao...)</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                            placeholder="Nhập tên kiểu chuyên đề" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="code" class="block text-sm font-medium text-gray-700">Mã nhận diện (Code) (VD: CB, NC, CH)</label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                            placeholder="Nhập mã nhận diện duy nhất" required>
                        <p class="mt-1 text-xs text-gray-500 italic">* Dùng để đối chiếu khi import dữ liệu từ Word/Excel.</p>
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Mô tả (Không bắt buộc)</label>
                        <textarea name="description" id="description" rows="4" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                            placeholder="Mô tả ngắn gọn về kiểu chuyên đề này...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end items-center mt-6 border-t pt-4">
                        <a href="{{ route('topic-types.index') }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900 underline">
                            Quay lại danh sách
                        </a>
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Lưu dữ liệu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>