<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chỉnh sửa Quyền: {{ $permission->name }}</h2>
    </x-slot>

    <div class="py-12 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tên Quyền</label>
                    <input type="text" name="name" value="{{ old('name', $permission->name) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                    @error('name') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>
                
                <div class="flex items-center justify-end mt-4">
                    <a href="{{ route('permissions.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Hủy</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>