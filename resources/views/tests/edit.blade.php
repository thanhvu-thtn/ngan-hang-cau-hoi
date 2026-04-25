<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa nội dung</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 py-10">
    <div class="max-w-4xl mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('tests.index') }}" class="text-blue-600 hover:underline">← Quay lại danh sách</a>
        </div>

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Chỉnh sửa nội dung bài test #{{ $test->id }}</h1>

            <form action="{{ route('tests.update', $test->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Bắt buộc phải có dòng này khi dùng hàm Update --}}

                <div class="mb-6">
                    <textarea id="test_content" name="content" class="w-full h-80 p-4 border rounded-lg mb-4">{{ old('content', $test->content) }}</textarea>

                    <div class="flex space-x-3">
                        <button type="button" onclick="initTinyMCE('test_content')"
                            class="px-4 py-2 bg-green-600 text-white text-xs rounded">Bật TinyMCE</button>
                        <button type="button" onclick="destroyAllTinyMCE()"
                            class="px-4 py-2 bg-red-500 text-white text-xs rounded">Tắt TinyMCE</button>
                        <button type="button" onclick="showPreview('test_content')"
                            class="px-4 py-2 bg-gray-800 text-white text-xs rounded">Xem trước (KaTeX)</button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700">Cập Nhật Thay
                    Đổi</button>
            </form>
            <div class="flex space-x-3 mb-4">
                <a href="{{ route('tests.pdf', $test->id) }}" target="_blank"
                    class="px-4 py-2 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700 transition-colors">
                    PDF Preview
                </a>
                <a href="{{ route('tests.docx', $test->id) }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
                    <i class="fas fa-file-word"></i> Xuất Word (.docx)
                </a>
            </div>
        </div>
    </div>

    {{-- Dùng lại script đã tối ưu --}}
    @include('partials.tinymce.editor')
</body>

</html>
