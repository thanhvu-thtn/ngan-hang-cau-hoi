<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Nội Dung Mới</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 py-10">
    <div class="max-w-4xl mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('tests.index') }}" class="text-blue-600 hover:underline">← Quay lại danh sách</a>
        </div>

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Tạo Nội Dung Test</h1>

            <form action="{{ route('tests.store') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <textarea id="test_content" name="content" class="w-full h-80 p-4 border rounded-lg mb-4">{{ old('content') }}</textarea>
                    
                    <div class="flex space-x-3">
                        <button type="button" onclick="initTinyMCE('test_content')" class="px-4 py-2 bg-green-600 text-white text-xs rounded">Bật TinyMCE</button>
                        <button type="button" onclick="destroyAllTinyMCE()" class="px-4 py-2 bg-red-500 text-white text-xs rounded">Tắt TinyMCE</button>
                        <button type="button" onclick="showPreview('test_content')" class="px-4 py-2 bg-gray-800 text-white text-xs rounded">Xem trước (KaTeX)</button>
                    </div>
                    @error('content')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">Lưu Nội Dung</button>
            </form>
        </div>
    </div>

    @include('partials.tinymce.editor') {{-- Sử dụng lại logic đã có --}}
</body>
</html>