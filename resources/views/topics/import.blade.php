<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nhập Chuyên đề từ file Excel
        </h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-r-md">
            <h3 class="text-sm font-bold text-blue-800">Hướng dẫn định dạng file Excel (.xlsx)</h3>
            <p class="text-sm text-blue-700 mt-2">Dòng đầu tiên (Tiêu đề) bắt buộc phải có 4 cột với tên tiếng Anh chính
                xác như sau:</p>
            <ul class="list-disc list-inside text-sm text-blue-700 mt-1 font-mono">
                <li>code <span class="text-gray-500 font-sans">(Mã chuyên đề - không được trùng)</span></li>
                <li>name <span class="text-gray-500 font-sans">(Tên chuyên đề)</span></li>
                <li>grade_code <span class="text-gray-500 font-sans">(Mã khối lớp gồm: 10, 11, 12)</span></li>
                <li>topic_type_code <span class="text-gray-500 font-sans">(Mã kiểu chuyên đề gồm: 1CB, 2NC, 3CH)</span>
                </li>
            </ul>
        </div>

        @if (session('error'))
            <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-800 ring-1 ring-inset ring-red-600/20">
                {{ session('error') }}
            </div>
        @endif

        @error('excel_file')
            <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-800 ring-1 ring-inset ring-red-600/20">
                {{ $message }}
            </div>
        @enderror

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
            <form action="{{ route('topics.import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chọn file từ máy tính</label>
                    <div
                        class="mt-1 flex justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-5 pb-6 hover:border-indigo-500 transition-colors bg-gray-50">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48" aria-hidden="true">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="file-upload"
                                    class="relative cursor-pointer rounded-md bg-white font-medium text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:text-indigo-500">
                                    <span>Tải file lên</span>
                                    <input id="file-upload" name="excel_file" type="file" class="sr-only"
                                        accept=".xlsx, .xls" required>
                                </label>
                                <p class="pl-1">hoặc kéo thả vào đây</p>
                            </div>
                            <p class="text-xs text-gray-500">XLSX, XLS lên đến 5MB</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6 border-t pt-4">
                    <a href="{{ route('topics.index') }}" class="mr-4 text-sm text-gray-600 mt-2 hover:underline">Hủy
                        bỏ</a>
                    <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 shadow-sm font-bold flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Tiến hành Import
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Script nhỏ để hiển thị tên file khi người dùng chọn
        document.getElementById('file-upload').addEventListener('change', function(e) {
            var fileName = e.target.files[0].name;
            // Thay vì xóa sạch innerHTML, chúng ta tìm đến thẻ span hiển thị thông báo
            // Hoặc đơn giản là đổi text của thẻ cha
            const label = e.target.closest('label');
            if (label) {
                label.querySelector('span').innerText = 'Đã chọn: ' + fileName;
            }
        });
    </script>
</x-app-layout>
