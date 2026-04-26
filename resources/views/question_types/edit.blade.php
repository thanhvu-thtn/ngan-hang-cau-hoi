<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chỉnh sửa loại câu hỏi</h2>
    </x-slot>

    <div class="py-12 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form action="{{ route('question-types.update', $questionType->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Tên loại câu hỏi</label>
                    <input type="text" name="name" value="{{ old('name', $questionType->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Mã nhận diện (Code)</label>
                    <input type="text" name="code" value="{{ old('code', $questionType->code) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Số lượng phương án lựa chọn</label>
                    <input type="number" name="num_choices" value="{{ old('num_choices', $questionType->num_choices) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                <div class="flex justify-end mt-6">
                    <a href="{{ route('question-types.index') }}" class="mr-4 text-sm text-gray-600 mt-2 hover:underline">Hủy</a>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 shadow-sm">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>