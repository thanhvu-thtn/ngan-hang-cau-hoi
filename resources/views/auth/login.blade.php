<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Ngân hàng câu hỏi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="mx-auto max-w-screen-xl px-4 py-16 sm:px-6 lg:px-8 w-full">
        <div class="mx-auto max-w-lg">

            <div class="text-center flex flex-col items-center">
                <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="Logo"
                    class="size-12 mb-4" />
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                    Đăng nhập hệ thống
                </h1>
                <p class="mx-auto mt-4 max-w-md text-gray-500">
                    Vui lòng đăng nhập để truy cập quản lý Ngân hàng câu hỏi.
                </p>
            </div>

            <form action="/login" method="POST"
                class="mb-0 mt-8 space-y-4 rounded-xl bg-white p-4 shadow-xl sm:p-6 lg:p-8">
                @csrf
                @error('email')
                    <div class="rounded-md bg-red-50 p-4 mb-4">
                        <div class="flex">
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">{{ $message }}</h3>
                            </div>
                        </div>
                    </div>
                @enderror
                <p class="text-center text-lg font-medium text-gray-700">Thông tin tài khoản</p>

                <div>
                    <label for="email" class="sr-only">Email</label>
                    <div class="relative">
                        <input type="email" name="email" id="email"
                            class="w-full rounded-lg border-gray-200 p-4 pe-12 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            placeholder="Nhập địa chỉ Email" required />
                        <span class="absolute inset-y-0 end-0 grid place-content-center px-4 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div>
                    <label for="password" class="sr-only">Mật khẩu</label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                            class="w-full rounded-lg border-gray-200 p-4 pe-12 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            placeholder="Nhập mật khẩu" required />
                        <span class="absolute inset-y-0 end-0 grid place-content-center px-4 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember" class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="remember" name="remember"
                            class="size-4 rounded-md border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                        <span class="text-sm text-gray-600">Ghi nhớ đăng nhập</span>
                    </label>

                    <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Quên mật
                        khẩu?</a>
                </div>

                <button type="submit"
                    class="block w-full rounded-lg bg-indigo-600 px-5 py-3 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                    Đăng nhập
                </button>
            </form>

        </div>
    </div>

</body>

</html>
