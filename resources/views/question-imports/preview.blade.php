<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Xem trước — Import câu hỏi
            </h2>
            <a href="{{ route('question-imports.create') }}"
                class="text-sm text-gray-600 hover:text-gray-800 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Tải file khác
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ============================================================ --}}
            {{-- SUMMARY BADGES                                               --}}
            {{-- ============================================================ --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ([
                    ['Tổng số', $summary['total'],   'bg-gray-100',   'text-gray-800'],
                    ['Hợp lệ',  $summary['valid'],   'bg-green-100',  'text-green-800'],
                    ['Đổi mã',  $summary['renamed'], 'bg-yellow-100', 'text-yellow-800'],
                    ['Lỗi',     $summary['error'],   'bg-red-100',    'text-red-800'],
                ] as [$label, $count, $bg, $text])
                    <div class="rounded-xl {{ $bg }} {{ $text }} px-5 py-4 flex items-center justify-between shadow-sm border border-white/60">
                        <span class="text-sm font-semibold">{{ $label }}</span>
                        <span class="text-2xl font-extrabold">{{ $count }}</span>
                    </div>
                @endforeach
            </div>

            {{-- ============================================================ --}}
            {{-- BẢNG PREVIEW                                                 --}}
            {{-- ============================================================ --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-base font-bold text-blue-800">Danh sách câu hỏi sẽ được import</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Chỉ những hàng <span class="text-green-700 font-semibold">Hợp lệ</span> và <span class="text-yellow-700 font-semibold">Đổi mã</span> sẽ được ghi vào cơ sở dữ liệu.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 text-left w-8">#</th>
                                <th class="px-4 py-3 text-left">Mã câu hỏi</th>
                                <th class="px-4 py-3 text-center">Loại</th>
                                <th class="px-4 py-3 text-center">Mức độ</th>
                                <th class="px-4 py-3 text-left">Objectives</th>
                                <th class="px-4 py-3 text-left">Ngữ cảnh dùng chung</th>
                                <th class="px-4 py-3 text-center">Trạng thái</th>
                                <th class="px-4 py-3 text-left">Nhận xét</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($rows as $i => $row)
                                @php
                                    $rowBg = match ($row['status']) {
                                        'valid'   => '',
                                        'renamed' => 'bg-yellow-50',
                                        'error'   => 'bg-red-50',
                                        default   => '',
                                    };
                                @endphp
                                <tr class="{{ $rowBg }} hover:brightness-95 transition">

                                    {{-- STT --}}
                                    <td class="px-4 py-3 text-gray-400 text-xs">{{ $i + 1 }}</td>

                                    {{-- Mã câu hỏi --}}
                                    <td class="px-4 py-3">
                                        <span class="font-semibold text-blue-700">{{ $row['code'] }}</span>
                                        @if ($row['status'] === 'renamed')
                                            <br>
                                            <span class="text-xs text-gray-400">→ lưu: <code class="bg-gray-100 px-1 rounded">{{ Str::limit($row['code_to_save'], 30) }}</code></span>
                                        @endif
                                    </td>

                                    {{-- Loại --}}
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800">
                                            {{ $row['question_type_code'] ?: '—' }}
                                        </span>
                                    </td>

                                    {{-- Mức độ --}}
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-800">
                                            {{ $row['cognitive_level_code'] ?: '—' }}
                                        </span>
                                    </td>

                                    {{-- Objectives --}}
                                    <td class="px-4 py-3 text-xs text-gray-600 max-w-xs">
                                        @if (!empty($row['objective_codes']))
                                            <div class="flex flex-wrap gap-1">
                                                @foreach ($row['objective_codes'] as $objCode)
                                                    <span class="bg-blue-50 text-blue-700 border border-blue-200 rounded px-1.5 py-0.5 font-mono text-[10px]">
                                                        {{ $objCode }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>

                                    {{-- Shared context --}}
                                    <td class="px-4 py-3 text-xs text-gray-600">
                                        @if (!empty($row['_shared_context_code']))
                                            <span class="bg-indigo-50 text-indigo-700 border border-indigo-200 rounded px-2 py-0.5 font-mono">
                                                {{ $row['_shared_context_code'] }}
                                            </span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>

                                    {{-- Trạng thái badge --}}
                                    <td class="px-4 py-3 text-center">
                                        @php
                                            [$badge, $badgeBg] = match ($row['status']) {
                                                'valid'   => ['✅ Hợp lệ',  'bg-green-100 text-green-800'],
                                                'renamed' => ['⚠️ Đổi mã',  'bg-yellow-100 text-yellow-800'],
                                                'error'   => ['❌ Lỗi',     'bg-red-100 text-red-800'],
                                                default   => [$row['status'], 'bg-gray-100 text-gray-700'],
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeBg }}">
                                            {{ $badge }}
                                        </span>
                                    </td>

                                    {{-- Nhận xét --}}
                                    <td class="px-4 py-3 text-xs text-gray-600 max-w-sm">
                                        {{ $row['remark'] }}
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- FOOTER: NÚT HÀNH ĐỘNG                                       --}}
            {{-- ============================================================ --}}
            <div class="flex items-center justify-between pb-12">
                <a href="{{ route('question-imports.create') }}"
                    class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Hủy, tải file khác
                </a>

                @php $canImport = $summary['valid'] + $summary['renamed'] > 0; @endphp

                @if ($canImport)
                    <form action="{{ route('question-imports.execute') }}" method="POST"
                        onsubmit="handleSubmit(this)">
                        @csrf
                        <input type="hidden" name="cache_key" value="{{ $cacheKey }}">
                        <button type="submit" id="confirm-btn"
                            class="inline-flex items-center gap-2 px-10 py-3 bg-blue-700 text-white font-bold rounded-lg hover:bg-blue-800 shadow-lg transition active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <span id="confirm-label">
                                Xác nhận import {{ $summary['valid'] + $summary['renamed'] }} câu hỏi
                            </span>
                        </button>
                    </form>
                @else
                    <div class="px-6 py-3 bg-gray-100 text-gray-400 font-semibold rounded-lg border border-gray-200 text-sm italic">
                        Không có câu hỏi hợp lệ để import.
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-app-layout>

<script>
    function handleSubmit(form) {
        const btn   = document.getElementById('confirm-btn');
        const label = document.getElementById('confirm-label');
        btn.disabled = true;
        label.textContent = 'Đang import...';
    }
</script>
