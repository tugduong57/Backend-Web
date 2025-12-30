@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h2 class="page-title">👨‍🎓 Quản lý Sinh Viên</h2>
        <p class="page-description">Thêm mới và xem danh sách sinh viên trong hệ thống</p>
    </div>

    <div class="row">
        <!-- Form thêm sinh viên -->
        <div class="col-md-4">
            <div class="card form-card">
                <div class="card-header">
                    <h3 class="card-title">➕ Thêm sinh viên mới</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('sinhvien.store') }}" method="POST" class="student-form">
                        @csrf

                        <div class="form-group">
                            <label for="ten_sinh_vien" class="form-label">
                                <i class="icon">👤</i> Tên sinh viên
                            </label>
                            <input type="text" id="ten_sinh_vien" name="ten_sinh_vien" class="form-control"
                                placeholder="Nhập họ tên sinh viên" required>
                            <small class="form-text">Ví dụ: Nguyễn Văn A</small>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="icon">📧</i> Email
                            </label>
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="Nhập địa chỉ email" required>
                            <small class="form-text">Ví dụ: student@tlu.edu.vn</small>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="icon">✅</i> Thêm sinh viên
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="icon">🔄</i> Nhập lại
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bảng danh sách sinh viên -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">📋 Danh sách sinh viên ({{ $danhSachSV->count() }})</h3>
                    <div class="card-tools">
                        <span class="badge badge-info">Tổng: {{ $danhSachSV->count() }} sinh viên</span>
                    </div>
                </div>

                <div class="card-body">
                    @if($danhSachSV->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="40%">👤 Tên sinh viên</th>
                                        <th width="35%">📧 Email</th>
                                        <th width="20%">📅 Ngày tạo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($danhSachSV as $index => $sv)
                                        <tr class="student-row">
                                            <td class="text-center">
                                                <span class="badge badge-primary">{{ $index + 1 }}</span>
                                            </td>
                                            <td>
                                                <div class="student-info">
                                                    <strong>{{ $sv->ten_sinh_vien }}</strong>
                                                    <small class="text-muted d-block">ID: {{ $sv->id }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="mailto:{{ $sv->email }}" class="email-link">
                                                    <i class="icon">✉️</i> {{ $sv->email }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="date-info" title="{{ $sv->created_at->format('d/m/Y H:i:s') }}">
                                                    {{ $sv->created_at->format('d/m/Y') }}
                                                    <small class="text-muted d-block">{{ $sv->created_at->format('H:i') }}</small>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">📭</div>
                            <h4>Chưa có sinh viên nào</h4>
                            <p>Hãy thêm sinh viên đầu tiên bằng form bên trái</p>
                        </div>
                    @endif
                </div>

                @if($danhSachSV->count() > 0)
                    <div class="card-footer">
                        <div class="table-footer">
                            <div class="footer-info">
                                <span class="text-muted">
                                    <i class="icon">🕐</i> Cập nhật lần cuối: {{ now()->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            <div class="footer-actions">
                                <button class="btn btn-sm btn-outline-info" onclick="window.print()">
                                    <i class="icon">🖨️</i> In danh sách
                                </button>
                                <button class="btn btn-sm btn-outline-success" onclick="exportToExcel()">
                                    <i class="icon">📥</i> Xuất Excel
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Form styles */
        .form-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-card .card-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }

        .student-form .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #ced4da;
            border-radius: 6px;
            padding: 10px 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        .form-text {
            color: #6c757d;
            font-size: 0.875rem;
            margin-top: 5px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, .3);
        }

        /* Table styles */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            border-top: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.5px;
        }

        .student-row {
            transition: all 0.3s;
        }

        .student-row:hover {
            background-color: rgba(0, 123, 255, .05);
            transform: translateX(5px);
        }

        .student-info {
            line-height: 1.4;
        }

        .email-link {
            color: #0066cc;
            text-decoration: none;
            transition: color 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .email-link:hover {
            color: #004080;
            text-decoration: underline;
        }

        .date-info {
            font-family: 'Courier New', monospace;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 12px;
            font-weight: 500;
        }

        .badge-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }

        /* Footer styles */
        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .footer-info {
            font-size: 0.875rem;
        }

        .footer-actions {
            display: flex;
            gap: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }

            .col-md-4,
            .col-md-8 {
                width: 100%;
            }

            .col-md-4 {
                margin-bottom: 20px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .table-footer {
                flex-direction: column;
                text-align: center;
            }
        }

        /* Page header */
        .page-header {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #007bff;
        }

        .page-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .page-description {
            color: #6c757d;
            font-size: 1rem;
        }
    </style>

    <script>
        function exportToExcel() {
            alert('Tính năng xuất Excel đang được phát triển!');
            // Trong thực tế, bạn có thể sử dụng thư viện như SheetJS
        }

        // Tự động focus vào input đầu tiên
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('ten_sinh_vien')?.focus();

            // Thêm hiệu ứng cho form submit
            const form = document.querySelector('.student-form');
            form.addEventListener('submit', function (e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="icon">⏳</i> Đang xử lý...';
                submitBtn.disabled = true;
            });
        });
    </script>
@endsection