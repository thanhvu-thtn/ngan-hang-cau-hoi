<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Phân quyền chuyên môn Giáo viên</h1>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg bg-white mt-6">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Giáo viên</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Các quyền đang có</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 text-right"><span class="sr-only">Thao tác</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($teachers as $teacher)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                            {{ $teacher->name }} <br>
                            <span class="text-xs text-gray-500">{{ $teacher->email }}</span>
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-500">
                            @php $directPerms = $teacher->getDirectPermissions(); @endphp
                            @if($directPerms->isEmpty())
                                <span class="text-gray-400 italic">Chưa có quyền riêng</span>
                            @else
                                <div class="flex flex-wrap gap-1">
                                    @foreach($directPerms as $perm)
                                        <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                            {{ permission_dictionary($perm->name) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium">
                            <a href="{{ route('teacher-permissions.edit', $teacher->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded border border-indigo-200">
                                Phân quyền
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $teachers->links() }}</div>
</x-app-layout>