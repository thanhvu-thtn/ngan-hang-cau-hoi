<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhập dữ liệu từ Word</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans antialiased text-gray-900">

    <div class="container mx-auto py-10 px-4">
        <div class="max-w-2xl mx-auto">
            
            <div class="mb-6">
                <a href="{{ route('tests.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay lại danh sách
                </a>
            </div>

            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6">
                    <h2 class="text-2xl font-bold text-white">Nhập dữ liệu từ Microsoft Word</h2>
                    <p class="text-blue-100 text-sm mt-1">Hệ thống sẽ tự động bóc tách nội dung bảng và xử lý hình ảnh.</p>
                </div>

                <form action="{{ route('tests.import-word') }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded">
                            <ul class="list-disc list-inside font-medium">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-8">
                        <label class="block text-gray-700 text-sm font-bold mb-3">Chọn file .docx</label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 hover:border-blue-400 transition-colors bg-gray-50 group">
                            <input type="file" name="word_file" id="word_file" accept=".docx" required
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-3 text-gray-600 font-medium" id="file-name-display">Kéo thả file vào đây hoặc click để chọn</p>
                                <p class="text-xs text-gray-400 mt-1 uppercase">Định dạng hỗ trợ: Word (.docx)</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-bold text-yellow-800">Lưu ý cấu trúc file Word:</h3>
                                <div class="mt-1 text-sm text-yellow-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Dữ liệu cần import phải nằm trong một <b>Bảng (Table)</b>.</li>
                                        <li>Bảng cần có ít nhất 2 cột (Cột 1: Bỏ qua, Cột 2: Nội dung sẽ được lưu).</li>
                                        <li>Hệ thống sẽ tự động quét và lưu hình ảnh, công thức toán học.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-10 rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Bắt đầu xử lý
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('word_file').addEventListener('change', function () {
            const displayElement = document.getElementById('file-name-display');
            if (this.files && this.files.length > 0) {
                displayElement.innerText = "Đã chọn: " + this.files[0].name;
                displayElement.classList.remove('text-gray-600');
                displayElement.classList.add('text-blue-600', 'font-bold');
            } else {
                displayElement.innerText = "Kéo thả file vào đây hoặc click để chọn";
                displayElement.classList.remove('text-blue-600', 'font-bold');
                displayElement.classList.add('text-gray-600');
            }
        });
    </script>
</body>
</html>