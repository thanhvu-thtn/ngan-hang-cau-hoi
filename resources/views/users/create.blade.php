<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Thêm người dùng mới</h1>
        <p class="mt-2 text-sm text-gray-700">Điền thông tin bên dưới để tạo tài khoản mới trong hệ thống.</p>
    </x-slot>

    <div class="max-w-2xl mx-auto mt-8">
        <div class="sm:flex sm:items-center mb-6">

            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('users.index') }}"
                    class="text-sm font-semibold leading-6 text-gray-900 hover:text-indigo-600">
                    &larr; Quay lại danh sách
                </a>
            </div>
        </div>

        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="px-4 py-6 sm:p-8">
                    <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        <div class="sm:col-span-4">
                            <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Họ và
                                tên</label>
                            <div class="mt-2">
                                <input type="text" name="name" id="name" value="{{ old('name') }}"
                                    class="block w-full rounded-md border-0 py-1.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset {{ $errors->has('name') ? 'ring-red-300 focus:ring-red-600' : 'ring-gray-300 focus:ring-indigo-600' }} focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6">
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-4">
                            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Địa chỉ
                                Email</label>
                            <div class="mt-2">
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="block w-full rounded-md border-0 py-1.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset {{ $errors->has('email') ? 'ring-red-300 focus:ring-red-600' : 'ring-gray-300 focus:ring-indigo-600' }} focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6">
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-4">
                            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Mật
                                khẩu</label>
                            <div class="mt-2">
                                <input type="password" name="password" id="password"
                                    class="block w-full rounded-md border-0 py-1.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset {{ $errors->has('password') ? 'ring-red-300 focus:ring-red-600' : 'ring-gray-300 focus:ring-indigo-600' }} focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6">
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-4">
                            <label for="password_confirmation"
                                class="block text-sm font-medium leading-6 text-gray-900">Nhập lại mật khẩu</label>
                            <div class="mt-2">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="block w-full rounded-md border-0 py-1.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                    </div>
                </div>

                <div
                    class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8 bg-gray-50 rounded-b-xl">
                    <button type="reset" class="text-sm font-semibold leading-6 text-gray-900">Hủy</button>
                    <button type="submit"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
