<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Xuất PDF Offline</title>



    <style>
        {!! $katexCss !!}
    </style>

    <style>
        @font-face {
            font-family: 'Times New Roman';
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            /* Tăng lên 1.2 (tăng khoảng ~11% so với 1.08) giúp chữ thoáng và dễ đọc hơn */
            line-height: 1.2;
            margin: 0;
            padding: 0;
            color: #000;
        }

        @page {
            size: A4;
            margin: 2cm;
        }

        .content-wrapper {
            width: 100%;
            word-wrap: break-word;
        }

        p {
            margin-top: 0;
            /* Set về 0 để khoảng cách giữa 2 thẻ <p> đúng bằng khoảng cách xuống dòng thông thường */
            margin-bottom: 0;
        }


        img {
            max-width: 100%;
            height: auto;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 6pt;
            margin-bottom: 6pt;
        }

        table,
        th,
        td {
            /* Ép buộc tất cả các ô trong bảng phải tuân thủ font và size này */
            font-family: "Times New Roman", Times, serif !important;
            font-size: 12pt !important;
            line-height: 1.2 !important;
            color: #000;

            /* Thêm viền đen chuẩn cho bảng */
            border: 1px solid black;

            /* Thêm khoảng cách để chữ không bị dính sát vào đường viền */
            padding: 5px 8px;

            /* Giúp các nội dung dài trong bảng tự động xuống dòng, không làm vỡ bố cục */
            word-wrap: break-word;
        }
    </style>
</head>

<body>
    <div class="content-wrapper" id="pdf-content">
        {!! $content !!}
    </div>

    <script>
        {!! $katexJs !!}
    </script>
    <script>
        {!! $autoRenderJs !!}
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            renderMathInElement(document.getElementById('pdf-content'), {
                delimiters: [{
                        left: '$$',
                        right: '$$',
                        display: true
                    },
                    {
                        left: '$',
                        right: '$',
                        display: false
                    },
                    {
                        left: '\\(',
                        right: '\\)',
                        display: false
                    },
                    {
                        left: '\\[',
                        right: '\\]',
                        display: true
                    }
                ],
                throwOnError: false
            });
        });
    </script>
</body>

</html>
