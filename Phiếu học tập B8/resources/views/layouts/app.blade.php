<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Quản lý Sinh Viên - Laravel' }}</title>
    <style>
        /* Reset và base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }

        /* Container chính */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        header {
            background: linear-gradient(135deg, #2c3e50, #4a6491);
            color: white;
            padding: 1.5rem 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo h1 {
            font-size: 1.8rem;
            font-weight: 600;
        }

        .logo p {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 0.2rem;
        }

        /* Navigation */
        nav {
            background-color: #343a40;
            padding: 0.8rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-links a:hover {
            background-color: #495057;
            transform: translateY(-2px);
        }

        .nav-links a.active {
            background-color: #007bff;
        }

        /* Main content area */
        main {
            padding: 2rem 0;
            min-height: calc(100vh - 200px);
        }

        /* Footer */
        footer {
            background-color: #2c3e50;
            color: white;
            padding: 1.5rem 0;
            margin-top: 2rem;
            text-align: center;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .copyright {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .footer-links {
            display: flex;
            gap: 1.5rem;
        }

        .footer-links a {
            color: #adb5bd;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .nav-links {
                flex-direction: column;
                gap: 0.5rem;
                align-items: center;
            }

            .nav-links a {
                width: 100%;
                text-align: center;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
            }
        }

        /* Additional utility classes */
        .text-center {
            text-align: center;
        }

        .mt-1 {
            margin-top: 0.5rem;
        }

        .mt-2 {
            margin-top: 1rem;
        }

        .mt-3 {
            margin-top: 1.5rem;
        }

        .mb-1 {
            margin-bottom: 0.5rem;
        }

        .mb-2 {
            margin-bottom: 1rem;
        }

        .mb-3 {
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <div class="container header-content">
            <div class="logo">
                <h1>📚 Quản lý Sinh Viên</h1>
                <p>Ứng dụng Laravel - Bài thực hành Chương 8</p>
            </div>
            <div class="user-info">
                <span>Hệ thống Quản lý Học tập</span>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav>
        <div class="container">
            <ul class="nav-links">
                <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">🏠 Trang Chủ</a></li>
                <li><a href="/about" class="{{ request()->is('about') ? 'active' : '' }}">📖 Giới Thiệu</a></li>
                <li><a href="/sinhvien" class="{{ request()->is('sinhvien') ? 'active' : '' }}">👨‍🎓 Quản lý Sinh
                        Viên</a></li>
                <li><a href="#" onclick="alert('Tính năng đang phát triển')">📊 Thống kê</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container footer-content">
            <div class="copyright">
                <p>&copy; 2025 - Khoa Công nghệ Thông tin - Trường Đại học Thủy Lợi</p>
                <p>Môn học: CSE485 - Công nghệ Web</p>
            </div>
            <div class="footer-links">
                <a href="#" onclick="alert('Liên hệ: cse485@tlu.edu.vn')">📧 Liên hệ</a>
                <a href="#" onclick="alert('Hướng dẫn sử dụng')">📘 Hướng dẫn</a>
                <a href="#" onclick="alert('Chính sách bảo mật')">🔒 Bảo mật</a>
            </div>
        </div>
    </footer>

    <!-- Optional JavaScript for interactive elements -->
    <script>
        // Thêm class active cho nav item hiện tại
        document.addEventListener('DOMContentLoaded', function () {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-links a');

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>

</html>