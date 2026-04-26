<nav class="bg-gray-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">

            <div class="flex items-center">
                <div class="shrink-0">
                    <svg class="size-8 text-indigo-500" viewBox="0 0 54 33" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M27 0c-7.2 0-11.7 3.6-13.5 10.8 2.7-3.6 5.85-4.95 9.45-4.05 2.054.513 3.522 2.004 5.147 3.653C30.744 13.09 33.808 16.2 40.5 16.2c7.2 0 11.7-3.6 13.5-10.8-2.7 3.6-5.85 4.95-9.45 4.05-2.054-.513-3.522-2.004-5.147-3.653C36.756 3.11 33.692 0 27 0zM13.5 16.2C6.3 16.2 1.8 19.8 0 27c2.7-3.6 5.85-4.95 9.45-4.05 2.054.513 3.522 2.004 5.147 3.653C17.244 29.29 20.308 32.4 27 32.4c7.2 0 11.7-3.6 13.5-10.8-2.7 3.6-5.85 4.95-9.45 4.05-2.054-.513-3.522-2.004-5.147-3.653C23.256 19.31 20.192 16.2 13.5 16.2z"
                            fill="currentColor" />
                    </svg>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="/main" aria-current="page"
                            class="rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white">Dashboard</a>
                        {{-- Các chức năng dành cho admin --}}
                        @role('admin')
                            <a href="{{ route('system_settings.index') }}"
                                class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                                Hệ thống
                            </a>
                            <a href="{{ route('users.index') }}"
                                class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                                Người dùng
                            </a>
                            <a href="{{ route('roles.index') }}"
                                class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                                Chức vụ
                            </a>
                            <a href="{{ route('permissions.index') }}"
                                class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                                Quyền
                            </a>
                            <a href="{{ route('grades.index') }}"
                                class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                                Khối lớp
                            </a>
                            <a href="{{ route('topic-types.index') }}"
                                class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                                Kiểu chuyên đề
                            </a>
                            <a href="{{ route('question-types.index') }}"
                                class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                                Kiểu câu hỏi
                            </a>
                        @endrole
                        {{-- Các chức năng dành cho team-leader --}}
                        @role('team-leader')
                            <a href="{{ route('teacher-permissions.index') }}"
                                class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                                Phân quyền chuyên môn
                            </a>
                            <a href="{{ route('topics.index') }}"
                                class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                                Chuyên đề
                            </a>
                        @endrole
                    </div>
                </div>
            </div>

            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">

                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">

                            <div class="relative ml-3" x-data="{ open: false }">

                                <button @click="open = !open" @click.outside="open = false"
                                    class="relative flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                                    <span class="absolute -inset-1.5"></span>
                                    <span class="sr-only">Mở menu người dùng</span>
                                    <img class="size-8 rounded-full"
                                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff"
                                        alt="Avatar" />
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95" style="display: none;"
                                    class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-none">

                                    <div class="ml-3">
                                        <div class="text-base font-medium leading-none text-white">
                                            {{ auth()->user()->name }}</div>
                                        <div class="mt-1 text-sm font-medium leading-none text-gray-400">
                                            {{ auth()->user()->email }}</div>

                                        <div class="mt-1 text-xs font-medium text-indigo-300">
                                            Chức vụ:
                                            {{ auth()->user()->roles->pluck('name')->map(fn($role) => role_dictionary($role))->join(', ') }}
                                        </div>
                                    </div>

                                    <form method="POST" action="/logout" class="m-0 mt-1">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 focus:bg-red-50 focus:outline-hidden transition-colors">
                                            Đăng xuất
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="-mr-2 flex md:hidden">
                <button type="button"
                    class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="md:hidden" id="mobile-menu">
        <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
            <a href="/main" class="block rounded-md bg-gray-900 px-3 py-2 text-base font-medium text-white"
                aria-current="page">Dashboard</a>
                {{-- Các chức năng dành cho admin --}}
            @role('admin')
                <a href="{{ route('system_settings.index') }}"
                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    Hệ thống
                </a>
                <a href="{{ route('users.index') }}"
                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    Người dùng
                </a>
                <a href="{{ route('roles.index') }}"
                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    Chức vụ
                </a>
                <a href="{{ route('permissions.index') }}"
                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    Quyền
                </a>
                <a href="{{ route('grades.index') }}"
                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    Khối lớp
                </a>
                <a href="{{ route('topic-types.index') }}"
                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    Kiểu chuyên đề
                </a>
                <a href="{{ route('question-types.index') }}"
                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    Kiểu câu hỏi
                </a>
            @endrole
            {{-- Các chức năng dành cho team-leader --}}
            @role('team-leader')
                <a href="{{ route('teacher-permissions.index') }}"
                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    Phân quyền chuyên môn
                </a>
                <a href="{{ route('topics.index') }}"
                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    Chuyên đề
                </a>
            @endrole
        </div>
        <div class="border-t border-gray-700 pb-3 pt-4">
            <div class="flex items-center px-5">
                <div class="shrink-0">
                    <img class="size-10 rounded-full"
                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff"
                        alt="">
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium leading-none text-white">{{ auth()->user()->name }}</div>
                    <div class="mt-1 text-sm font-medium leading-none text-gray-400">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1 px-2">
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit"
                        class="block w-full text-left rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">
                        Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
