//
import './bootstrap';

// 1. Nhúng CSS của KaTeX (Vite sẽ tự động xử lý và copy các font chữ đi kèm)
import 'katex/dist/katex.min.css';

// Import hàm auto-render của KaTeX
import renderMathInElement from 'katex/dist/contrib/auto-render.mjs';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Gán nó vào đối tượng window để các file .blade.php có thể gọi được
window.renderMathInElement = renderMathInElement;

// 3. Chạy script khi giao diện đã tải xong
document.addEventListener("DOMContentLoaded", function() {
    // Tìm tất cả các thẻ HTML có chứa class 'format-katex'
    const mathElements = document.querySelectorAll('.format-katex');
    
    mathElements.forEach((elem) => {
        renderMathInElement(elem, {
            // Định nghĩa các dấu hiệu nhận biết công thức toán
            delimiters: [
                {left: '$$', right: '$$', display: true}, // Công thức đứng riêng 1 dòng
                {left: '$', right: '$', display: false},  // Công thức nằm cùng dòng chữ
                {left: '\\(', right: '\\)', display: false},
                {left: '\\[', right: '\\]', display: true}
            ],
            // Bỏ qua lỗi nếu người dùng gõ sai cú pháp, tránh sập trang
            throwOnError: false
        });
    });
});

