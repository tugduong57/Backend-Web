<?php
// Bắt đầu session
session_start();

// Kiểm tra xem SESSION lưu tên đăng nhập có tồn tại không
if (isset($_SESSION['username'])) {

    // Lấy username từ SESSION (sanitize để tránh XSS)
    $loggedInUser = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');

    // In ra lời chào mừng
    echo "<h1>Chào mừng trở lại, $loggedInUser!</h1>";
    echo "<p>Bạn đã đăng nhập thành công.</p>";

    // Link tạm thời để "Đăng xuất" (quay về login.html)
    echo '<a href="login.html">Đăng xuất (Tạm thời)</a>';
} else {
    // Nếu chưa đăng nhập, chuyển hướng về trang login
    header('Location: login.html');
    exit;
}
?>