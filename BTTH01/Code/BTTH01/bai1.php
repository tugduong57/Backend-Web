<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách các loài hoa - CSE485</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4 text-primary">Danh sách các loài hoa (Từ thư mục images)</h1>
        
        <?php
        // Đường dẫn thư mục ảnh
        $dir = "images/";
        
        // Kiểm tra thư mục có tồn tại không
        if (is_dir($dir)){
            // Lấy tất cả các file có đuôi ảnh
            $images = glob($dir . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
            
            if (count($images) > 0) {
                echo '<div class="row">';
                foreach ($images as $imagePath) {
                    // Xử lý tên file để làm Tên hoa (VD: images/Hoa_hong.jpg -> Hoa hồng)
                    $fileName = basename($imagePath); // Lấy tên file gốc
                    $flowerName = pathinfo($fileName, PATHINFO_FILENAME); // Bỏ đuôi .jpg
                    $flowerName = str_replace('_', ' ', $flowerName); // Thay _ bằng khoảng trắng
                    
                    echo '
                    <div class="col-md-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="'.$imagePath.'" class="card-img-top" style="height: 200px; object-fit: cover;" alt="'.$flowerName.'">
                            <div class="card-body">
                                <h5 class="card-title text-center">'.$flowerName.'</h5>
                                <p class="card-text text-muted text-center small">Mô tả đang cập nhật...</p>
                            </div>
                        </div>
                    </div>';
                }
                echo '</div>';
            } else {
                echo '<div class="alert alert-warning">Không tìm thấy ảnh nào trong thư mục images/.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Thư mục images/ không tồn tại.</div>';
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>