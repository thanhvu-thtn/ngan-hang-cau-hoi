<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Services\ImageService;
use App\Services\PdfService;
use App\Services\WordService;
use Illuminate\Http\Request;


class TestController extends Controller
{
    //
    public function index()
    {
        // Lấy danh sách tests, sắp xếp mới nhất lên đầu, phân trang 10 dòng/trang
        $tests = Test::orderBy('id', 'desc')->paginate(10);

        return view('tests.index', compact('tests'));
    }

    public function create()
    {
        return view('tests.create');
    }

    public function store(Request $request)
    {
        // Chỉ validate content
        $request->validate([
            'content' => 'required',
        ], [
            'content.required' => 'Bạn chưa nhập nội dung bài test.',
        ]);
        // Xử lý ảnh trong nội dung trước khi lưu
        $imageService = new ImageService;
        $processedContent = $imageService->processHtmlContent($request->content);

        // Lưu trực tiếp
        Test::create([
            'content' => $processedContent,
        ]);

        return redirect()->route('tests.index')->with('success', 'Đã lưu thành công!');
    }

    // Hiển thị form soạn thảo nội dung đã có
    public function edit($id)
    {
        $test = Test::findOrFail($id);

        return view('tests.edit', compact('test'));
    }

    // Cập nhật nội dung mới vào Database
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required',
        ]);

        $test = Test::findOrFail($id);

        // Xử lý ảnh trong nội dung trước khi cập nhật
        $imageService = new ImageService;
        $processedContent = $imageService->processHtmlContent($request->content);

        $test->update([
            'content' => $processedContent,
        ]);

        return redirect()->route('tests.index')->with('success', 'Đã cập nhật nội dung thành công!');
    }

    // Xóa bài test
    public function destroy($id)
    {
        $test = Test::findOrFail($id);
        $imageService = new ImageService;
        $imageService->deleteImagesFromHtml($test->content); // Xóa ảnh vật lý có trong nội dung
        $test->delete();

        return redirect()->route('tests.index')->with('success', 'Đã xóa bài test thành công!');
    }

    public function exportPdf($id, PdfService $pdfService)
    {
        $test = Test::findOrFail($id);

        // Tạo file PDF từ content của bài test
        $pdfData = $pdfService->generatePdfFromHtml($test->content);

        // Trả về trình duyệt hiển thị trực tiếp (inline)
        return response($pdfData)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="test-'.$id.'.pdf"');
    }

    public function exportWord($id, WordService $wordService)
    {
        $test = Test::findOrFail($id);

        // Tạo file Word từ content của bài test
        $wordData = $wordService->generateDocxFromHtml($test->content);

        // Trả về trình duyệt hiển thị trực tiếp (inline)
        return response()->download($wordData['path'], 'test-'.$id.'.docx');
    }

    public function upload()
    {
        return view('tests.upload');
    }

    public function importFromWord(Request $request, WordService $wordService, ImageService $imageService)
    {
        // 1. Validate file đầu vào
        $request->validate([
            'word_file' => 'required|file|mimes:docx|max:10240', // File docx, tối đa 10MB
        ]);

        // 2. Lấy mảng [key, value] từ WordService
        $dataArray = $wordService->importFromWord($request->file('word_file'));

        // Biến đếm số lượng bản ghi đã import thành công
        $count = 0;

        foreach ($dataArray as $item) {
            // Lấy giá trị của cột Value (nếu trống thì bỏ qua)
            $htmlValue = $item['value'] ?? '';
            
            if (empty(trim(strip_tags($htmlValue)))) {
                continue; 
            }

            // 3. Dùng ImageService có sẵn để quét và lưu ảnh vật lý lên Server
            $processedContent = $imageService->processHtmlContent($htmlValue);

            // 4. Lưu vào cơ sở dữ liệu (map Value vào cột Content của model Test)
            Test::create([
                'content' => $processedContent,
            ]);

            $count++;
        }

        // 5. Chuyển hướng về trang danh sách kèm thông báo
        return redirect()->route('tests.index')
                         ->with('success', "Đã nhập thành công {$count} bản ghi từ file Word!");
    }
}
