<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <style>
        /* CSS thuần cho Word */
        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.5;
        }
        p {
            margin-bottom: 12pt;
            text-align: justify;
        }
        img {
            max-width: 100%;
            height: auto;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="content">
        {!! $content !!}
    </div>
</body>
</html>