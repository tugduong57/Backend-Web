<?php
// TODO 1: Khởi động session (phải trước bất kỳ output nào)
session_start();

// TODO 2: Kiểm tra xem form đã được gửi chưa
if (isset($_POST['username'])) {

    // TODO 3: Lấy dữ liệu từ $_POST
    $user = isset($_POST['username']) ? trim($_POST['username']) : '';
    $pass = isset($_POST['password']) ? $_POST['password'] : '';

    // TODO 4: Kiểm tra logic đăng nhập (giả lập)
    if ($user === 'admin' && $pass === '1234567') {

        // TODO 5: Lưu tên username vào SESSION
        $_SESSION['username'] = $user;

        // TODO 6: Chuyển hướng sang trang chào mừng
        header('Location: welcome.php');
        exit;

    } else {
        // Thất bại => quay về login với thông báo lỗi
        header('Location: login.html?error=1');
        exit;
    }

} else {
    // TODO 7: Nếu truy cập trực tiếp thì "đá" về login.html
    header('Location: login.html');
    exit;
}
?>