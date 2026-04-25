<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Chỉnh sửa chức vụ</h1>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('roles.index') }}" class="text-sm font-semibold text-gray-900 hover:text-indigo-600">&larr;
                Quay lại</a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto mt-8">
        <div class="sm:flex sm:items-center mb-6">


        </div>

        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="px-4 py-6 sm:p-8">
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Tên chức vụ</label>
                    <div class="mt-2">
                        <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}"
                            class="block w-full rounded-md border-0 py-1.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center justify-end gap-x-6 border-t px-4 py-4 sm:px-8 bg-gray-50 rounded-b-xl">
                    <button type="submit"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Cập
                        nhật chức vụ</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
