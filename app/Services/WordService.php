<?php

namespace App\Services;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class WordService
{
    public function generateDocxFromHtml($content)
    {
        $uuid = (string) Str::uuid(); // Ép kiểu về chuỗi cho chắc chắn
        
        // 1. Tạo đường dẫn tuyệt đối đến thư mục chứa file tạm
        $tempFolderPath = storage_path('app/word-template');

        // 2. Kiểm tra và TẠO THƯ MỤC nếu nó chưa tồn tại (tạo bằng PHP thuần)
        if (!file_exists($tempFolderPath)) {
            // Tham số 0775 cấp quyền ghi, true cho phép tạo thư mục lồng nhau
            mkdir($tempFolderPath, 0775, true); 
        }

        // Định nghĩa đường dẫn file
        $htmlFile = $tempFolderPath . '/' . $uuid . '.html';
        $docxFile = $tempFolderPath . '/' . $uuid . '.docx';
        $referenceDoc = public_path('storage/pandoc/custom-reference.docx'); // Đảm bảo file này có thật

        // 3. Ép Ảnh thành Base64 (Giữ nguyên logic cũ của bạn)
        $content = preg_replace_callback('/src="\/storage\/(.*?)"/', function($matches) {
            $imagePath = storage_path('app/public/' . $matches[1]);
            if (file_exists($imagePath)) {
                $mime = mime_content_type($imagePath);
                $base64 = base64_encode(file_get_contents($imagePath));
                return 'src="data:' . $mime . ';base64,' . $base64 . '"';
            }
            return $matches[0];
        }, $content);

        // 4. Render HTML và lưu file
        $html = View::make('partials.word.export', ['content' => $content])->render();
        
        // Lệnh này bây giờ sẽ chạy mượt mà vì thư mục đã chắc chắn tồn tại
        file_put_contents($htmlFile, $html);

        // 3. Chạy Pandoc
        // --from html+tex_math_dollars: Giúp Pandoc hiểu $...$ là công thức toán
        $command = [
            'pandoc', 
            $htmlFile, 
            '-o', $docxFile, 
            '--from', 'html+tex_math_dollars+tex_math_single_backslash', // <-- Sửa dòng này
            '--reference-doc=' . $referenceDoc
        ];

        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return [
            'path' => $docxFile,
            'uuid' => $uuid,
            'html_path' => $htmlFile
        ];
    }

    /**
     * Import dữ liệu từ file Word, bóc tách bảng lấy Key và Value
     * * @param \Illuminate\Http\UploadedFile $file
     * @return array
     */
    public function importFromWord($file)
    {
        $uuid = (string) Str::uuid();
        $tempFolderPath = storage_path('app/public/word-template');

        if (!file_exists($tempFolderPath)) {
            mkdir($tempFolderPath, 0775, true);
        }

        // 1. Sao chép file Word vào thư mục tạm
        $fileName = $uuid . '.' . $file->getClientOriginalExtension();
        $file->move($tempFolderPath, $fileName);
        
        $wordPath = $tempFolderPath . '/' . $fileName;
        $htmlPath = $tempFolderPath . '/' . $uuid . '.html';

        // 2. Chạy Pandoc để dịch Word sang HTML
        // --embed-resources --standalone: Giúp gộp ảnh thành Base64 vào thẳng file HTML
        // --extract-media: Nếu bản Pandoc cũ không hỗ trợ embed thì dùng cái này (nhưng embed tốt hơn)
        $command = [
            'pandoc',
            $wordPath,
            '-o', $htmlPath,
            '--from', 'docx',
            '--to', 'html',
            '--embed-resources',
            '--standalone'
        ];

        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // 3. Đọc file HTML và bóc tách dữ liệu bảng
        $htmlContent = file_get_contents($htmlPath);
        
        // Sử dụng DOMDocument để parse HTML
        $dom = new \DOMDocument();
        // Tắt báo lỗi HTML5 không chuẩn
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">' . $htmlContent);
        libxml_clear_errors();

        $results = [];
        $tables = $dom->getElementsByTagName('table');

        foreach ($tables as $table) {
            $rows = $table->getElementsByTagName('tr');
            foreach ($rows as $index => $row) {
                $cols = $row->getElementsByTagName('td');
                
                // Nếu không có td (có thể là th), thử lấy th
                if ($cols->length == 0) {
                    $cols = $row->getElementsByTagName('th');
                }

                // Kiểm tra nếu hàng có ít nhất 2 cột
                if ($cols->length >= 2) {
                    $key = $this->getInnerHtml($cols->item(0));
                    $value = $this->getInnerHtml($cols->item(1));

                    // Bỏ qua hàng tiêu đề nếu nó chính là chữ 'key' và 'value'
                    if (strtolower(trim(strip_tags($key))) == 'key' && strtolower(trim(strip_tags($value))) == 'value') {
                        continue;
                    }

                    $results[] = [
                        'key' => trim($key),
                        'value' => trim($value)
                    ];
                }
            }
        }

        // Xóa file tạm sau khi xong (tùy chọn)
        @unlink($wordPath);
        @unlink($htmlPath);

        return $results;
    }

    /**
     * Hàm phụ trợ để lấy nội dung bên trong một node bao gồm cả các thẻ HTML (ảnh, công thức)
     */
    private function getInnerHtml($node)
    {
        $innerHTML = "";
        $children  = $node->childNodes;
        foreach ($children as $child) {
            $innerHTML .= $node->ownerDocument->saveHTML($child);
        }
        return $innerHTML;
    }
}