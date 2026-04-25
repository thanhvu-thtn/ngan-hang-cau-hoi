{{-- Dùng cho popup xem trước nội dung --}}
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
        <div class="p-8 overflow-y-auto flex-grow format-katex text-lg" id="preview-content-area">
        </div>
    </div>
</div>

@once
    <script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
@endonce

<script>
    // ========================================================
    // QUẢN LÝ TINYMCE (BẬT / TẮT)
    // ========================================================

    // Hàm bật TinyMCE cho một ID cụ thể
    function initTinyMCE(editorId) {
        // 1. Tắt tất cả các TinyMCE đang hoạt động trên trang trước
        tinymce.remove();

        // 2. Khởi tạo lại TinyMCE cho đúng textarea được yêu cầu
        tinymce.init({
            selector: '#' + editorId,
            license_key: 'gpl',
            promotion: false,
            branding: false,
            relative_urls: false,
            remove_script_host: true,
            document_base_url: '/', // Giúp TinyMCE luôn hiểu gốc là từ root
            height: 400,

            // 1. Thêm chữ 'table' vào danh sách plugins
            plugins: 'advlist autolink lists link image charmap code anchor pagebreak textcolor wordcount table', 
            
            // 2. Thêm chữ 'table' vào thanh toolbar (bạn có thể đặt cạnh nút image)
            toolbar: 'undo redo | formatselect | bold italic textcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table | code',

            menu: {
                view: {
                    title: 'View',
                    items: 'code | visualaid visualchars visualblocks | fullscreen'
                }
            },

            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            image_advtab: true,
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');

                input.onchange = function() {
                    var file = this.files[0];
                    var reader = new FileReader();

                    reader.onload = function() {
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);

                        cb(blobInfo.blobUri(), {
                            title: file.name
                        });
                    };
                    reader.readAsDataURL(file);
                };

                input.click();
            }
        });
    }

    // Hàm tắt tất cả TinyMCE trên trang
    function destroyAllTinyMCE() {
        tinymce.remove();
    }

    // ========================================================
    // QUẢN LÝ POPUP PREVIEW
    // ========================================================

    // Hàm hiển thị Preview
    function showPreview(editorId) {
        let content = '';

        // Kiểm tra xem TinyMCE có đang hoạt động trên ID này không
        if (typeof tinymce !== 'undefined' && tinymce.get(editorId)) {
            content = tinymce.get(editorId).getContent();
        } else {
            content = document.getElementById(editorId).value;
        }

        const contentArea = document.getElementById('preview-content-area');
        contentArea.innerHTML = content;

        document.getElementById('global-preview-modal').classList.remove('hidden');

        if (window.renderMathInElement) {
            window.renderMathInElement(contentArea, {
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
        }
    }

    // Hàm đóng Preview
    function closePreview(event = null) {
        document.getElementById('global-preview-modal').classList.add('hidden');
    }
</script>
