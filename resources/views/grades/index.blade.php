<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Quản lý Khối lớp</h1>
    </x-slot>

    <div class="sm:flex sm:items-center mb-8">
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('grades.create') }}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">+ Thêm khối lớp</a>
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
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">ID</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tên khối</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Mã (Code)</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-right"><span class="sr-only">Thao tác</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($grades as $grade)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-500">{{ $grade->id }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-gray-900">{{ $grade->name }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-indigo-600 font-mono">{{ $grade->code }}</td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium">
                            <a href="{{ route('grades.edit', $grade->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Sửa</a>
                            <form action="{{ route('grades.destroy', $grade->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khối lớp này?');">
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
    <div class="mt-4">{{ $grades->links() }}</div>
</x-app-layout>