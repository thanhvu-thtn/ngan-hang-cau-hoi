@props(['items', 'selected' => []])

{{-- Giao diện nền trắng, chữ đen hoàn toàn --}}
<div class="treeview-wrapper border border-gray-300 rounded-lg overflow-hidden bg-white"
    style="color: #1f2937 !important;">

    {{-- Search box: Ép nền trắng (#ffffff) thay vì xám nhạt --}}
    <div class="p-3 border-b border-gray-200 bg-white" style="background-color: #ffffff;">
        <input type="text" id="tree-search" placeholder="Tìm kiếm nhanh chuyên đề, mục tiêu..."
            class="w-full px-4 py-3 text-base border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-none placeholder-gray-400 bg-white text-gray-900 shadow-sm">
    </div>

    {{-- Container chứa cây: Tăng chiều cao lên max-h-[70vh] --}}
    <div id="treeview-container" class="max-h-[70vh] overflow-y-auto p-5 custom-scrollbar bg-white"
        style="background-color: #ffffff;">
        <div id="tree-loading" class="text-center py-10 text-gray-500">
            <svg class="animate-spin h-8 w-8 mx-auto mb-3 text-blue-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span class="text-lg">Đang tải dữ liệu...</span>
        </div>
    </div>
</div>

{{-- Dữ liệu truyền từ Server sang JS --}}
<script type="application/json" id="tree-data-raw">
    @json($items)
</script>

<style>
    /* Đường kẻ nối sơ đồ cây */
    .line-v {
        position: absolute;
        left: -0.75rem;
        top: 0;
        bottom: 0;
        border-left: 1px solid #d1d5db;
    }

    .line-h {
        position: absolute;
        left: -0.75rem;
        top: 1.25rem;
        width: 0.75rem;
        border-top: 1px solid #d1d5db;
    }

    .rotate-90 {
        transform: rotate(90deg);
    }

    /* Làm đẹp thanh cuộn */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

@once
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rawData = JSON.parse(document.getElementById('tree-data-raw').textContent);
            const selectedIds = @json($selected);
            const container = document.getElementById('treeview-container');
            const searchInput = document.getElementById('tree-search');

            // Hàm bổ trợ: Bóc hết tag HTML để đưa vào thuộc tính search
            function stripTags(html) {
                let doc = new Array(html); // Trick để xử lý chuỗi
                return html.replace(/<[^>]*>?/gm, '').replace(/\n|\r/g, ' ').toLowerCase();
            }

            function createNodeHtml(item, level = 0) {
                const hasChildren = item.children && item.children.length > 0;
                const isLeaf = item.is_leaf || false;
                const isSelected = selectedIds.includes(String(item.id)) || selectedIds.includes(Number(item.id));

                let displayContent = item.label;

                // XỬ LÝ 1: Nếu KHÔNG PHẢI objective, bóc hết thẻ <p> và </p>
                if (!isLeaf) {
                    displayContent = displayContent.replace(/<\/?p[^>]*>/gi, '');
                }

                // XỬ LÝ 2: Tạo label sạch để search (không chứa HTML)
                const safeSearchLabel = stripTags(item.label);

                return `
            <div class="tree-node relative ${level > 0 ? 'ml-6' : ''}" data-label="${safeSearchLabel.replace(/"/g, '&quot;')}">
                ${level > 0 ? '<div class="line-v"></div><div class="line-h"></div>' : ''}
                
                <div class="flex items-start py-1.5 px-2 rounded cursor-pointer hover:bg-gray-100 transition-colors">
                    ${hasChildren ? `
                            <button type="button" class="btn-toggle flex-shrink-0 z-10 w-6 h-6 flex items-center justify-center mr-2 mt-0.5 bg-white border border-gray-300 rounded shadow-sm text-gray-500 hover:text-blue-600 focus:outline-none">
                                <svg class="w-3 h-3 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        ` : '<span class="w-6 mr-2 flex-shrink-0"></span>'}

                    <label class="flex items-start flex-1 cursor-pointer m-0">
                        <input type="checkbox" 
                               ${isLeaf ? 'name="objective_ids[]"' : ''} 
                               value="${item.id}" 
                               ${isSelected ? 'checked' : ''}
                               class="node-cb w-4 h-4 mt-1 text-blue-600 bg-white border-gray-400 rounded focus:ring-blue-500 cursor-pointer flex-shrink-0">
                        
                        <div class="ml-2 text-sm flex-1 break-words ${isLeaf ? 'font-bold text-gray-900 objective-label' : 'font-medium text-gray-800'}">
                            ${displayContent}
                        </div>
                    </label>
                </div>

                ${hasChildren ? `
                        <div class="child-container hidden mt-1">
                            ${item.children.map(child => createNodeHtml(child, level + 1)).join('')}
                        </div>
                    ` : ''}
            </div>
        `;
            }

            // Render toàn bộ cây
            container.innerHTML = rawData.map(node => createNodeHtml(node)).join('');

            // Khởi chạy KaTeX sau khi HTML đã được đưa vào DOM
            function runKatex() {
                if (typeof renderMathInElement === 'function') {
                    const objectiveLabels = container.querySelectorAll('.objective-label');
                    objectiveLabels.forEach(label => {
                        renderMathInElement(label, {
                            delimiters: [{
                                    left: '$$',
                                    right: '$$',
                                    display: true
                                },
                                {
                                    left: '$',
                                    right: '$',
                                    display: false
                                },
                                {
                                    left: '\\(',
                                    right: '\\)',
                                    display: false
                                },
                                {
                                    left: '\\[',
                                    right: '\\]',
                                    display: true
                                }
                            ],
                            throwOnError: false
                        });
                    });
                }
            }

            // Chạy lần đầu
            setTimeout(runKatex, 200);

            // --- CÁC LOGIC KHÁC (GIỮ NGUYÊN) ---
            container.addEventListener('click', (e) => {
                const btn = e.target.closest('.btn-toggle');
                if (btn) {
                    const node = btn.closest('.tree-node');
                    const childContainer = node.querySelector('.child-container');
                    const svg = btn.querySelector('svg');
                    childContainer.classList.toggle('hidden');
                    svg.classList.toggle('rotate-90');
                }
            });

            container.addEventListener('change', (e) => {
                if (e.target.classList.contains('node-cb')) {
                    const isChecked = e.target.checked;
                    const parent = e.target.closest('.tree-node');
                    const children = parent.querySelectorAll('.child-container input[type="checkbox"]');
                    children.forEach(cb => cb.checked = isChecked);
                }
            });

            searchInput.addEventListener('input', (e) => {
                const term = e.target.value.toLowerCase();
                const allNodes = container.querySelectorAll('.tree-node');

                if (!term) {
                    allNodes.forEach(n => {
                        n.classList.remove('hidden');
                        n.querySelector('.child-container')?.classList.add('hidden');
                        n.querySelector('.btn-toggle svg')?.classList.remove('rotate-90');
                    });
                    return;
                }

                allNodes.forEach(node => {
                    const label = node.getAttribute('data-label');
                    if (label.includes(term)) {
                        node.classList.remove('hidden');
                        let p = node.parentElement.closest('.tree-node');
                        while (p) {
                            p.classList.remove('hidden');
                            p.querySelector('.child-container')?.classList.remove('hidden');
                            p.querySelector('.btn-toggle svg')?.classList.add('rotate-90');
                            p = p.parentElement.closest('.tree-node');
                        }
                    } else {
                        node.classList.add('hidden');
                    }
                });
            });
        });
    </script>
@endonce
