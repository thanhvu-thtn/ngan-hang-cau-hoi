<?php

namespace App\Services;

use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelService
{
    /**
     * Chuyển đổi file Excel thành chuỗi JSON
     *
     * * @param string $filePath Đường dẫn tạm của file tải lên
     * @return string|false Trả về JSON hoặc false nếu lỗi
     */
    public function convertToJson($filePath)
    {
        try {
            // Đọc file Excel từ đường dẫn
            $spreadsheet = IOFactory::load($filePath);

            // Lấy sheet đầu tiên và chuyển thành mảng (giữ nguyên tọa độ)
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $result = [];
            $headers = [];

            // Duyệt qua từng dòng
            foreach ($sheetData as $rowIndex => $row) {
                // Dòng 1 là Tiêu đề cột (Key)
                if ($rowIndex === 1) {
                    $headers = $row;

                    continue;
                }

                // Kiểm tra xem dòng có rỗng hoàn toàn không (người dùng hay để dư dòng trống ở cuối)
                if (empty(array_filter($row))) {
                    continue;
                }

                $rowData = [];
                foreach ($row as $colIndex => $cellValue) {
                    // Dùng tiêu đề cột làm Key, nếu ô tiêu đề trống thì dùng chữ cái (A, B, C...)
                    $columnName = $headers[$colIndex] ?? $colIndex;

                    // Loại bỏ khoảng trắng thừa ở 2 đầu giá trị (nếu có)
                    $rowData[$columnName] = is_string($cellValue) ? trim($cellValue) : $cellValue;
                }

                $result[] = $rowData;
            }

            // Trả về JSON (JSON_UNESCAPED_UNICODE giúp hiển thị tiếng Việt có dấu chuẩn xác)
            return json_encode($result, JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            // Bắt lỗi nếu file không đúng định dạng hoặc bị hỏng
            // Trong thực tế, bạn có thể Log lỗi ra file ở đây: Log::error($e->getMessage());
            return false;
        }
    }

    public function exportTopics($topics)
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Danh sach chuyen de');

        // 1. Tạo dòng Tiêu đề
        $headers = ['Mã số', 'Tên chuyên đề', 'Khối lớp', 'Phân loại'];
        $sheet->fromArray($headers, null, 'A1');

        // (Tùy chọn) Làm đậm dòng tiêu đề
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        // 2. Đổ dữ liệu từ Database vào Excel
        $row = 2; // Bắt đầu từ dòng 2
        foreach ($topics as $topic) {
            $sheet->setCellValue('A'.$row, $topic->code);
            $sheet->setCellValue('B'.$row, $topic->name);
            $sheet->setCellValue('C'.$row, $topic->grade->name ?? '-');
            $sheet->setCellValue('D'.$row, $topic->topicType->name ?? '-');
            $row++;
        }

        // 3. Tự động căn chỉnh độ rộng cột cho đẹp
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // 4. Tạo response để tải file về trình duyệt (Tránh lưu rác trên server)
        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        // Cấu hình Header để trình duyệt hiểu đây là file Excel tải về
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="danh-sach-chuyen-de.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    public function exportTopicContents($contents)
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Danh sach Noi dung');

        // 1. Tạo dòng Tiêu đề (Đúng các cột bạn yêu cầu)
        $headers = [
            'Mã nội dung',
            'Tên nội dung',
            'Mã chuyên đề',
            'Tên chuyên đề',
            'Khối lớp',
            'Phân loại',
        ];
        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        // 2. Đổ dữ liệu
        $row = 2;
        foreach ($contents as $item) {
            $sheet->setCellValue('A'.$row, $item->code);
            $sheet->setCellValue('B'.$row, $item->name);
            $sheet->setCellValue('C'.$row, $item->topic->code ?? '-');
            $sheet->setCellValue('D'.$row, $item->topic->name ?? '-');
            $sheet->setCellValue('E'.$row, $item->topic->grade->code ?? '-');
            $sheet->setCellValue('F'.$row, $item->topic->topicType->code ?? '-');
            $row++;
        }

        // 3. Định dạng tự động giãn cột
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="danh-sach-noi-dung-chuyen-de.xlsx"',
        ]);
    }
}
