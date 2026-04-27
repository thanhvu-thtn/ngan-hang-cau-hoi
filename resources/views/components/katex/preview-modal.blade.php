{{-- Giao diện Modal Preview --}}
<div id="global-preview-modal"
    class="fixed inset-0 z-50 hidden bg-black bg-opacity-60 flex items-center justify-center transition-opacity"
    onclick="closePreview(event)">
    <div class="bg-white w-11/12 max-w-5xl h-[85vh] rounded-xl shadow-2xl flex flex-col"
        onclick="event.stopPropagation()">
        <div class="flex justify-between items-center p-4 border-b bg-gray-50 rounded-t-xl">
            <h3 class="text-xl font-bold text-gray-800">Xem trước nội dung</h3>
            <button type="button" onclick="closePreview()"
                class="text-gray-400 hover:text-red-600 text-2xl font-bold focus:outline-none">&times;</button>
        </div>
        <div class="p-8 overflow-y-auto flex-grow format-katex text-lg prose max-w-none" id="preview-content-area">
        </div>
    </div>
</div>

<script>
    // ========================================================
    // CHỨC NĂNG PREVIEW TEXTAREA & KATEX
    // ========================================================

    /**
     * Mở modal và hiển thị nội dung từ một textarea cụ thể
     * @param {string} elementId - ID của thẻ textarea cần lấy dữ liệu
     */
    function previewContent(elementId) {
        // 1. Lấy dữ liệu thuần từ textarea
        const textarea = document.getElementById(elementId);
        if (!textarea) {
            console.error('Không tìm thấy element với ID:', elementId);
            return;
        }
        
        // Chuyển đổi ký tự xuống dòng (\n) thành thẻ <br> để hiển thị đúng HTML
        let content = textarea.value.replace(/\n/g, '<br>');

        // 2. Đưa nội dung vào vùng hiển thị trong modal
        const contentArea = document.getElementById('preview-content-area');
        contentArea.innerHTML = content;

        // 3. Hiển thị modal
        document.getElementById('global-preview-modal').classList.remove('hidden');

        // 4. Gọi thư viện KaTeX để render công thức toán học (nếu có thư viện trên trang)
        if (window.renderMathInElement) {
            window.renderMathInElement(contentArea, {
                delimiters: [
                    { left: '$$', right: '$$', display: true },
                    { left: '$', right: '$', display: false },
                    { left: '\\(', right: '\\)', display: false },
                    { left: '\\[', right: '\\]', display: true }
                ],
                throwOnError: false
            });
        }
    }

    // Hàm đóng Modal Preview
    function closePreview(event = null) {
        document.getElementById('global-preview-modal').classList.add('hidden');
    }
</script>