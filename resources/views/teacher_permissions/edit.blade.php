<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Phân quyền cho: <span class="text-indigo-600">{{ $teacher->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form action="{{ route('teacher-permissions.update', $teacher->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <h3 class="text-lg font-medium text-gray-900 mb-4">Danh sách quyền chuyên môn</h3>
                <p class="text-sm text-gray-500 mb-6">Tích chọn các thao tác mà giáo viên này được phép thực hiện trên hệ thống.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-gray-200 pt-4">
                    @foreach ($permissions as $permission)
                        <div class="relative flex items-start">
                            <div class="flex h-6 items-center">
                                <input id="perm_{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}" type="checkbox" 
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                                    {{ in_array($permission->name, $userDirectPermissions) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm leading-6">
                                <label for="perm_{{ $permission->id }}" class="font-medium text-gray-900 cursor-pointer">
                                    {{ permission_dictionary($permission->name) }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end mt-8 border-t border-gray-100 pt-4">
                    <a href="{{ route('teacher-permissions.index') }}" class="mr-4 text-sm text-gray-600 mt-2 hover:underline">Hủy bỏ</a>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 shadow-sm">
                        Lưu quyền hạn
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>