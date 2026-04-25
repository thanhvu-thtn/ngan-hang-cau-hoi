<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Phân quyền người dùng: {{ $user->name }}</h1>
        <p class="text-gray-500">{{ $user->email }}</p>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-8">
        <form action="{{ route('users.update-roles', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="bg-white shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Danh sách chức vụ</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach ($roles as $role)
                        <label class="relative flex items-start p-4 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <div class="flex h-6 items-center">
                                <input name="roles[]" value="{{ $role->name }}" type="checkbox"
                                    {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                    class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                            </div>
                            <div class="ml-3 text-sm">
                                <span class="font-bold text-gray-900">{{ role_dictionary($role->name) }}</span>
                                <p class="text-gray-500 text-xs mt-1">Mã: {{ $role->name }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="mt-8 flex items-center justify-end gap-x-4 border-t pt-6">
                    <a href="{{ route('users.index') }}" class="text-sm font-semibold leading-6 text-gray-900">Hủy
                        bỏ</a>
                    <button type="submit"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        Xác nhận phân quyền
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
