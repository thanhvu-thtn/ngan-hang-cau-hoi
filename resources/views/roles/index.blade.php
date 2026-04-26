<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Quản lý Chức vụ (Roles)</h1>
        <p class="mt-2 text-sm text-gray-700">Danh sách các nhóm quyền hạn trong hệ thống.</p>
    </x-slot>

    <div class="sm:flex sm:items-center mb-8">
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('roles.create') }}"
                class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">+
                Thêm chức vụ</a>
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
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Tên chức
                        vụ</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Ngày tạo</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @foreach ($roles as $role)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-bold text-indigo-600">
                            {{ $role->name }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                            {{ $role->created_at->format('d/m/Y') }}</td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium">
                            <a href="{{ route('roles.edit', $role->id) }}"
                                class="text-indigo-600 hover:text-indigo-900 mr-4">Sửa</a>
                            <a href="{{ route('roles.assign-permissions', $role->id) }}"
                                class="text-green-600 hover:text-green-900 mr-4">Phân quyền</a>
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="inline-block"
                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa chức vụ này?');">
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
    <div class="mt-4">{{ $roles->links() }}</div>
</x-app-layout>
