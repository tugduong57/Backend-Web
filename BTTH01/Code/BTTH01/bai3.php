<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách điểm danh K65 HTTT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center text-success mb-4 text-uppercase">Danh sách sinh viên (Từ file CSV)</h3>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Họ</th>
                        <th>Tên</th>
                        <th>Lớp</th>
                        <th>Email</th>
                        <th>Khóa học</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $filename = '65HTTT_Danh_sach_diem_danh.csv';
                    
                    if (file_exists($filename)) {
                        // Mở file chế độ đọc (r)
                        if (($handle = fopen($filename, "r")) !== FALSE) {
                            
                            // Đọc dòng đầu tiên làm tiêu đề (nếu cần xử lý riêng thì giữ lại biến $headers)
                            $headers = fgetcsv($handle, 1000, ","); 
                            
                            // Đọc các dòng dữ liệu
                            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                echo "<tr>";
                                foreach ($data as $cell) {
                                    // htmlspecialchars để tránh lỗi hiển thị nếu có ký tự đặc biệt
                                    echo "<td>" . htmlspecialchars($cell) . "</td>";
                                }
                                echo "</tr>";
                            }
                            fclose($handle);
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center text-danger fw-bold'>
                                Lỗi: Không tìm thấy file 65HTTT_Danh_sach_diem_danh.csv <br>
                                Hãy chắc chắn bạn đã để file này cùng thư mục với file php.
                              </td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>