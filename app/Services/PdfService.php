<?php

namespace App\Services;

use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\View;

class PdfService
{
    public function generatePdfFromHtml($content)
    {
        // ==========================================
        // 1. ÉP ẢNH THÀNH BASE64
        // ==========================================
        $content = preg_replace_callback('/src="\/storage\/(.*?)"/', function($matches) {
            $imagePath = storage_path('app/public/' . $matches[1]);
            if (file_exists($imagePath)) {
                $mime = mime_content_type($imagePath);
                $base64 = base64_encode(file_get_contents($imagePath));
                return 'src="data:' . $mime . ';base64,' . $base64 . '"';
            }
            return $matches[0];
        }, $content);


        // ==========================================
        // 2. ÉP FONT KATEX THÀNH BASE64 (Khắc phục triệt để file://)
        // ==========================================
        $katexCss = file_get_contents(public_path('vendor/katex/katex.min.css'));
        
        // Quét tất cả các lệnh url(fonts/...) trong file CSS
        $katexCss = preg_replace_callback('/url\([\'"]?fonts\/([^\'"\)]+)[\'"]?\)/i', function($matches) {
            // Lấy tên file font (ví dụ: KaTeX_AMS-Regular.woff2)
            $fileName = $matches[1];
            
            // Cắt bỏ các tham số rác nếu có (ví dụ: ?v=0.16.9 hoặc #iefix)
            $fileName = explode('?', $fileName)[0];
            $fileName = explode('#', $fileName)[0];

            $fontPath = public_path('vendor/katex/fonts/' . $fileName);
            
            if (file_exists($fontPath)) {
                $extension = strtolower(pathinfo($fontPath, PATHINFO_EXTENSION));
                $mime = 'font/' . $extension; // Mặc định woff2 -> font/woff2
                if ($extension === 'ttf') {
                    $mime = 'font/truetype';
                }
                
                // Đọc file font và mã hóa Base64
                $base64 = base64_encode(file_get_contents($fontPath));
                
                // Trả về dạng url("data:font/woff2;base64,.....")
                return 'url("data:' . $mime . ';base64,' . $base64 . '")';
            }
            return $matches[0]; // Trả lại nguyên bản nếu không tìm thấy file
        }, $katexCss);


        // ==========================================
        // 3. TIẾN HÀNH XUẤT PDF
        // ==========================================
        $katexJs = file_get_contents(public_path('vendor/katex/katex.min.js'));
        $autoRenderJs = file_get_contents(public_path('vendor/katex/auto-render.min.js'));

        $html = View::make('partials.pdf.export', [
            'content' => $content,
            'katexCss' => $katexCss,
            'katexJs' => $katexJs,
            'autoRenderJs' => $autoRenderJs,
        ])->render();

        return Browsershot::html($html)
            ->delay(1500) 
            ->format('A4')
            ->margins(20, 20, 20, 20)
            ->printBackground()
            ->pdf();
    }
}