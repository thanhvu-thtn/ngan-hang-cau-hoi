<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Xác nhận dữ liệu Import chuyên đề
            </h2>
            <span class="text-sm bg-gray-200 px-3 py-1 rounded-full text-gray-600">
                ID phiên: {{ substr($importId, 0, 8) }}...
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-4 shadow sm:rounded-lg border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500 uppercase font-bold">Tổng số dòng</p>
                    <p class="text-2xl font-semibold">{{ count($data) }}</p>
                </div>
                <div class="bg-white p-4 shadow sm:rounded-lg border-l-4 border-green-500">
                    <p class="text-sm text-gray-500 uppercase font-bold">Hợp lệ (Sẽ ghi)</p>
                    <p class="text-2xl font-semibold text-green-600">
                        {{ collect($data)->where('status', 'OK')->count() }}
                    </p>
                </div>
                <div class="bg-white p-4 shadow sm:rounded-lg border-l-4 border-red-500">
                    <p class="text-sm text-gray-500 uppercase font-bold">Lỗi (Sẽ bỏ qua)</p>
                    <p class="text-2xl font-semibold text-red-600">
                        {{ collect($data)->where('status', 'Error')->count() }}
                    </p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">STT</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mã (Gốc -> Dự kiến)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tên chuyên đề</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Khối / Kiểu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái & Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($data as $index => $row)
                                <tr class="{{ $row['status'] === 'Error' ? 'bg-red-50' : ($row['original_code'] !== $row['final_code'] ? 'bg-yellow-50' : '') }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <span class="text-gray-400 line-through">{{ $row['original_code'] }}</span>
                                        <svg class="w-4 h-4 inline text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                        <span class="text-indigo-600">{{ $row['final_code'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $row['name'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 py-1 bg-gray-100 rounded text-xs">{{ $row['grade_code'] }}</span>
                                        <span class="px-2 py-1 bg-gray-100 rounded text-xs">{{ $row['topic_type_code'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($row['status'] === 'OK')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Sẵn sàng
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Lỗi
                                            </span>
                                        @endif
                                        
                                        @if($row['message'])
                                            <p class="text-xs mt-1 {{ $row['status'] === 'Error' ? 'text-red-600' : 'text-yellow-700 italic' }}">
                                                {{ $row['message'] }}
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end items-center gap-4 border-t">
                    <a href="{{ route('topics.index') }}" class="text-sm text-gray-600 hover:underline">
                        Hủy bỏ và quay lại
                    </a>
                    
                    <form action="{{ route('topics.import.save') }}" method="POST">
                        @csrf
                        <input type="hidden" name="import_id" value="{{ $importId }}">
                        
                        @if(collect($data)->where('status', 'OK')->count() > 0)
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 shadow-sm font-bold flex items-center shadow-lg transform transition hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Xác nhận và Ghi vào hệ thống
                            </button>
                        @else
                            <button type="button" disabled class="bg-gray-400 text-white px-6 py-2 rounded-md cursor-not-allowed font-bold">
                                Không có dữ liệu hợp lệ để ghi
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>