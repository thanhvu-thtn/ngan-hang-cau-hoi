<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nhập Yêu cầu cần đạt từ file Word</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border p-8">
                <form action="{{ route('objectives.preview.word') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Chọn file Word (.docx)</label>
                        <input type="file" name="word_file" accept=".docx" required
                               class="w-full border border-gray-300 p-2 rounded-md">
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('objectives.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Hủy bỏ</a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-md hover:bg-indigo-700">
                            Tiếp tục (Xem trước)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>