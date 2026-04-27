<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Xác nhận dữ liệu Import</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 uppercase text-xs">
                            <th class="border p-3">Mã Chuyên đề</th>
                            <th class="border p-3">Mã Nội dung</th>
                            <th class="border p-3">Tên Nội dung</th>
                            <th class="border p-3">Kết quả kiểm tra</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $row)
                        <tr class="{{ $row['status'] == 'ERROR' ? 'bg-red-50' : ($row['status'] == 'WARNING' ? 'bg-yellow-50' : '') }}">
                            <td class="border p-3">{{ $row['topic_code'] }}</td>
                            <td class="border p-3">{{ $row['code'] }}</td>
                            <td class="border p-3">{{ $row['name'] }}</td>
                            <td class="border p-3">
                                @if($row['status'] == 'ERROR')
                                    <span class="text-red-600 font-bold">✘ {{ $row['message'] }}</span>
                                @elseif($row['status'] == 'WARNING')
                                    <span class="text-yellow-600 font-bold">⚠ {{ $row['message'] }}</span>
                                @else
                                    <span class="text-green-600 font-bold">✔ Hợp lệ</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-8 flex justify-end gap-4">
                    <a href="{{ route('topic-contents.import.form') }}" class="px-6 py-2 border rounded-lg">Quay lại</a>
                    <form action="{{ route('topic-contents.import.save') }}" method="POST">
                        @csrf
                        <input type="hidden" name="import_id" value="{{ $importId }}">
                        <button type="submit" class="bg-green-600 text-white px-8 py-2 rounded-lg font-bold shadow-lg">Xác nhận Lưu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>