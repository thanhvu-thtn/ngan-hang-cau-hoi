<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Xem trước dữ liệu nhập</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border p-6">
                
                <div class="mb-4 flex justify-between items-center">
                    <p class="text-sm text-gray-600">Những dòng có nền xanh nhạt sẽ được lưu. Nền đỏ sẽ bị bỏ qua.</p>
                    <div class="flex space-x-2">
                        <form action="{{ route('objectives.cancel.word') }}" method="POST">
                            @csrf
                            <input type="hidden" name="uuid" value="{{ $uuid }}">
                            <button type="submit" class="bg-red-100 text-red-700 px-6 py-2 rounded font-bold hover:bg-red-200">Hủy nhập</button>
                        </form>

                        <form action="{{ route('objectives.save.word') }}" method="POST">
                            @csrf
                            <input type="hidden" name="uuid" value="{{ $uuid }}">
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded font-bold hover:bg-indigo-700 shadow-lg">Lưu dữ liệu hợp lệ</button>
                        </form>
                    </div>
                </div>

                <table class="min-w-full border divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 border text-left text-xs font-bold text-gray-500 uppercase">Mã ND C.Đề</th>
                            <th class="px-4 py-2 border text-left text-xs font-bold text-gray-500 uppercase">Mã YCCĐ</th>
                            <th class="px-4 py-2 border text-left text-xs font-bold text-gray-500 uppercase">Mô tả</th>
                            <th class="px-4 py-2 border text-left text-xs font-bold text-gray-500 uppercase">Kết luận</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($validatedData as $row)
                            @php
                                $bgClass = $row->status == 'pass' ? ($row->message != 'Hợp lệ' ? 'bg-yellow-50' : 'bg-green-50') : 'bg-red-50';
                                $textClass = $row->status == 'pass' ? ($row->message != 'Hợp lệ' ? 'text-yellow-700' : 'text-green-700') : 'text-red-600 font-bold';
                            @endphp
                            <tr class="{{ $bgClass }}">
                                <td class="px-4 py-3 border text-sm font-mono">{{ $row->topic_content_code }}</td>
                                <td class="px-4 py-3 border text-sm font-mono">{{ $row->objective_code }}</td>
                                <td class="px-4 py-3 border text-sm format-katex max-w-md">
                                    {!! $row->objective_description !!}
                                </td>
                                <td class="px-4 py-3 border text-sm {{ $textClass }}">
                                    {{ $row->message }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>