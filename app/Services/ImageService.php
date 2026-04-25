<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DOMDocument;

class ImageService
{
    /**
     * Quét và xử lý toàn bộ hình ảnh trong nội dung HTML
     */
    public function processHtmlContent($htmlContent)
    {
        if (empty($htmlContent)) {
            return $htmlContent;
        }

        // Bỏ qua cảnh báo của DOMDocument nếu HTML bị lỗi cấu trúc nhẹ
        libxml_use_internal_errors(true);
        
        $dom = new DOMDocument();
        // Mẹo thêm thẻ meta để giữ nguyên font chữ Tiếng Việt (UTF-8) không bị lỗi
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        libxml_clear_errors();

        $images = $dom->getElementsByTagName('img');
        $hasChanges = false;

        foreach ($images as $img) {
            $src = $img->getAttribute('src');

            // Bỏ qua nếu ảnh đã nằm sẵn trong storage của hệ thống chúng ta
            if (str_starts_with($src, asset('storage/images'))) {
                continue;
            }

            $newSrc = null;

            // TRƯỜNG HỢP 1: Ảnh là chuỗi Base64 (TinyMCE tự động upload lên dạng này)
            if (preg_match('/^data:image\/(\w+);base64,/', $src, $matches)) {
                $extension = $matches[1]; // Lấy đuôi file (png, jpg...)
                // Cắt bỏ phần đầu, chỉ lấy data base64 thực sự
                $base64Data = substr($src, strpos($src, ',') + 1);
                $imageData = base64_decode($base64Data);
                
                $newSrc = $this->saveImageToStorage($imageData, $extension);
            } 
            // TRƯỜNG HỢP 2: Ảnh là Link từ Internet
            elseif (filter_var($src, FILTER_VALIDATE_URL)) {
                try {
                    $imageData = file_get_contents($src);
                    if ($imageData !== false) {
                        // Cố gắng bóc tách đuôi file từ URL, nếu không có mặc định là png
                        $extension = pathinfo(parse_url($src, PHP_URL_PATH), PATHINFO_EXTENSION);
                        if (empty($extension)) {
                            $extension = 'png'; 
                        }
                        
                        $newSrc = $this->saveImageToStorage($imageData, $extension);
                    }
                } catch (\Exception $e) {
                    // Nếu link chết hoặc bị chặn tải, bỏ qua không làm gì
                    continue;
                }
            }

            // Nếu lưu thành công, đổi lại thuộc tính src của thẻ <img>
            if ($newSrc) {
                //$img->setAttribute('src', asset('storage/' . $newSrc));
                $img->setAttribute('src', '/storage/' . $newSrc);
                $img->removeAttribute('data-mce-src'); // Xóa rác của TinyMCE nếu có
                $hasChanges = true;
            }
        }

        // Nếu có thay đổi, lưu lại HTML mới
        if ($hasChanges) {
            $modifiedHtml = $dom->saveHTML();
            // Cắt bỏ thẻ <?xml... chúng ta thêm vào lúc đầu
            $htmlContent = str_replace('<?xml encoding="utf-8" ?>', '', $modifiedHtml);
        }

        return $htmlContent;
    }

    /**
     * Lưu dữ liệu ảnh vật lý vào thư mục
     */
    private function saveImageToStorage($imageData, $extension)
    {
        $datePath = date('Y/m/d'); // Format: YY/MM/DD
        $uuid = Str::uuid()->toString();
        $filename = "images/{$datePath}/{$uuid}.{$extension}";

        // Lưu vào thư mục public của storage (storage/app/public/images/...)
        Storage::disk('public')->put($filename, $imageData);

        return $filename; // Trả về đường dẫn để gắn vào src
    }

    // Thêm vào trong class ImageService

/**
 * Xóa toàn bộ ảnh vật lý có trong đoạn HTML
 */
public function deleteImagesFromHtml($htmlContent)
{
    if (empty($htmlContent)) {
        return;
    }

    libxml_use_internal_errors(true);
    $dom = new \DOMDocument();
    $dom->loadHTML('<?xml encoding="utf-8" ?>' . $htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();

    $images = $dom->getElementsByTagName('img');

    foreach ($images as $img) {
        $src = $img->getAttribute('src');

        // Kiểm tra xem ảnh có phải là ảnh nội bộ (nằm trong thư mục storage/images) không
        if (str_contains($src, 'storage/images/')) {
            
            // Tách chuỗi để lấy đường dẫn tương đối tính từ thư mục storage/app/public
            // Ví dụ: /storage/images/2026/04/25/abc.png -> lấy được: images/2026/04/25/abc.png
            $parts = explode('storage/', $src);
            $storagePath = end($parts);

            // Tiến hành xóa file nếu tồn tại
            if (Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->delete($storagePath);
            }
        }
    }
}
}