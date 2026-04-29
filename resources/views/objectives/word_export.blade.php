<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<body>
    <h1 align="center">DANH SÁCH MÃ CÁC YÊU CẦU CẦN ĐẠT</h1>
    <h2 align="center">VẬT LÍ – {{ $headerInfo['grade'] }} – {{ $headerInfo['type'] }}</h2>

    @php $topicIndex = 1; @endphp
    @foreach ($groupedData as $topicId => $objsInTopic)
        @php $firstInTopic = $objsInTopic->first(); @endphp

        {{-- Thẻ h3 tương đương với Tiêu đề cấp 1 trong Word --}}
        <h3>{{ $topicIndex }}. Chuyên đề: {{ $firstInTopic->topicContent->topic->name }}</h3>

        @php
            $contentGroups = $objsInTopic->groupBy('topic_content_id');
            $contentIndex = 1;
        @endphp

        @foreach ($contentGroups as $contentId => $objectives)
            @php $firstInContent = $objectives->first(); @endphp

            {{-- Thẻ h4 tương đương với Tiêu đề cấp 2 trong Word --}}
            <h4>{{ $topicIndex }}.{{ $contentIndex }}. Nội dung: {{ $firstInContent->topicContent->name }}</h4>

            <table border="1" width="100%">
                <thead>
                    <tr>
                        <th width="50">STT</th>
                        <th>Yêu cầu cần đạt</th>
                        <th width="150">Mã định danh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($objectives as $idx => $obj)
                        <tr>
                            <td align="center">{{ $idx + 1 }}</td>
                            <td>
                                {!! $obj->description !!}
                            </td>
                            <td align="center">
                                <b>{{ $obj->code }}</b>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @php $contentIndex++; @endphp
        @endforeach

        @php $topicIndex++; @endphp
    @endforeach
</body>

</html>
