<x-app-layout>
    {{-- Nếu layout của bạn có slot "header", hãy đặt tiêu đề vào đây --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Danh sách câu hỏi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Header của trang Index --}}
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-800">Quản lý câu hỏi</h1>
                        <div class="flex space-x-2">
                            {{-- Nút mở Modal Filter (Treeview nằm trong này) --}}
                            <button type="button" 
                                    onclick="document.getElementById('filter-modal').classList.remove('hidden')"
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                Bộ lọc nâng cao
                            </button>

                            <a href="{{ route('questions.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition shadow-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Thêm câu hỏi
                            </a>
                        </div>
                    </div>

                    {{-- Bảng danh sách câu hỏi (Giữ nguyên logic của bạn) --}}
                    <div class="overflow-x-auto border rounded-xl shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            {{-- ... nội dung table của bạn ... --}}
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $questions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CHỨA TREEVIEW --}}
    <div id="filter-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            {{-- Lớp nền mờ --}}
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="document.getElementById('filter-modal').classList.add('hidden')"></div>

            {{-- Đổi max-w-2xl thành max-w-[90vw] để chiếm 90% chiều rộng màn hình --}}
            <div class="relative bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-[90vw] w-full">
                <form action="{{ route('questions.index') }}" method="GET">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        {{-- Tiêu đề và nút tắt (X) --}}
                        <div class="flex justify-between items-center mb-4 border-b pb-3">
                            <h3 class="text-2xl font-bold text-gray-900">Lọc theo Yêu cầu cần đạt</h3>
                            <button type="button" onclick="document.getElementById('filter-modal').classList.add('hidden')" class="text-gray-400 hover:text-red-500 transition">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        {{-- Xóa bg-gray-50, nền hoàn toàn trắng --}}
                        <div class="bg-white rounded-lg">
                            <x-treeview.objective-selector :items="$treeData" :selected="request('objective_ids', [])" />
                        </div>
                    </div>
                    
                    {{-- Footer của Modal đổi thành nền trắng --}}
                    <div class="bg-white px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-6 py-2.5 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition">
                            Áp dụng lọc
                        </button>
                        <button type="button" onclick="document.getElementById('filter-modal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                            Hủy
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>