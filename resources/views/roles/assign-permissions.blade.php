<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Thiết lập quyền cho: <span class="text-indigo-600">{{ role_dictionary($role->name) }}</span>
        </h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <form action="{{ route('roles.update-permissions', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Danh sách quyền hạn</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($permissions as $permission)
                    <div class="flex items-center p-3 border rounded-lg hover:bg-gray-50 transition-colors">
                        <input type="checkbox" 
                               name="permissions[]" 
                               value="{{ $permission->name }}" 
                               id="p-{{ $permission->id }}"
                               class="h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                               {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                        
                        <label for="p-{{ $permission->id }}" class="ml-3 cursor-pointer">
                            <span class="block text-sm font-semibold text-gray-700">
                                {{ permission_dictionary($permission->name) }}
                            </span>
                            <span class="block text-xs text-gray-400">
                                ({{ $permission->name }})
                            </span>
                        </label>
                    </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-end mt-8 pt-4 border-t">
                    <a href="{{ route('roles.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Quay lại</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg shadow-sm">
                        Lưu thiết lập
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>