<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị - Danh sách hoa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center text-primary mb-4 text-uppercase">Quản trị danh sách hoa</h2>
        
        <div class="card shadow">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Danh sách ảnh hiện có</h5>
                <a href="#" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Thêm mới</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center" style="width: 5%">#</th>
                            <th scope="col" style="width: 25%">Tên Hoa (Từ tên file)</th>
                            <th scope="col" class="text-center" style="width: 20%">Hình ảnh</th>
                            <th scope="col">Đường dẫn</th>
                            <th scope="col" class="text-center" style="width: 15%">Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // 1. Đường dẫn tới thư mục ảnh (đi ra ngoài 1 cấp)
                        $imageDir = '../images/';

                        // 2. Kiểm tra và lấy danh sách file ảnh
                        if (is_dir($imageDir)) {
                            $images = glob($imageDir . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);

                            if (count($images) > 0) {
                                foreach ($images as $index => $imagePath) {
                                    // Xử lý tên file để hiển thị cho đẹp
                                    $fileName = basename($imagePath); // VD: hoa_hong.jpg
                                    $nameDisplay = pathinfo($fileName, PATHINFO_FILENAME); // VD: hoa_hong
                                    $nameDisplay = str_replace('_', ' ', $nameDisplay); // VD: hoa hong
                                    $nameDisplay = ucwords($nameDisplay); // VD: Hoa Hong

                                    echo '<tr>';
                                    // Cột STT
                                    echo '<td class="text-center">' . ($index + 1) . '</td>';
                                    
                                    // Cột Tên hoa
                                    echo '<td class="fw-bold text-primary">' . $nameDisplay . '</td>';
                                    
                                    // Cột Hình ảnh (Thumbnail)
                                    echo '<td class="text-center">
                                            <img src="' . $imagePath . '" alt="' . $fileName . '" class="img-thumbnail" style="height: 80px; width: auto;">
                                          </td>';
                                    
                                    // Cột Đường dẫn file
                                    echo '<td class="text-muted fst-italic">' . $imagePath . '</td>';
                                    
                                    // Cột Chức năng (Icon Sửa/Xóa - Demo giao diện)
                                    echo '<td class="text-center">
                                            <a href="#" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                                          </td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="5" class="text-center text-danger">Không tìm thấy ảnh nào trong thư mục images!</td></tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5" class="text-center text-danger">Thư mục images không tồn tại!</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>