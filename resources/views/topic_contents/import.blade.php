<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nhập Nội dung từ Excel</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow sm:rounded-xl border border-gray-200">
                <form action="{{ route('topic-contents.import.preview') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Chọn file Excel (.xlsx)</label>
                        <input type="file" name="file" class="w-full border p-2 rounded-lg" required>
                        <p class="text-xs text-gray-500 mt-2">File cần có các cột: <b>topic_code, code, name</b></p>
                    </div>
                    
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('topic-contents.index') }}" class="px-4 py-2 text-gray-600">Hủy</a>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold">Tiếp tục</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>