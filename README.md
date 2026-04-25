## Preview textarea: @include(partials.tinymce.editor)
Trong đó đã viết sẵn đoạn javascript tự động bật windows previews.
# Cách gọi:onclick="showPreview('editor1')" 

## Bật tắt editor: onclick="initTinyMCE('editor2')" / onclick="destroyAllTinyMCE()"

## ImagaService->processHtmlContent($htmlContent)

## ImageService->deleteImagesFromHtml($htmlContent)

## Sử dụng PdfService để xuất một nội dung nào đó ra pdf  // Tạo file PDF từ content của bài test
        $pdfData = $pdfService->generatePdfFromHtml($test->content);
