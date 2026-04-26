<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Cài đặt Hệ thống (Settings)</h1>
    </x-slot>

    <div class="sm:flex sm:items-center mb-8">
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('system_settings.create') }}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">+ Thêm cấu hình</a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 ring-1 ring-inset ring-green-600/20 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg bg-white">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Từ khóa (Key)</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Giá trị (Value)</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-right"><span class="sr-only">Thao tác</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($settings as $setting)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-bold text-indigo-600">{{ $setting->key }}</td>
                        <td class="px-3 py-4 text-sm text-gray-500 break-words max-w-xs">{{ Str::limit($setting->value, 50) }}</td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium">
                            <a href="{{ route('system_settings.edit', $setting->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Sửa</a>
                            <form action="{{ route('system_settings.destroy', $setting->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa cấu hình này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $settings->links() }}</div>
</x-app-layout>