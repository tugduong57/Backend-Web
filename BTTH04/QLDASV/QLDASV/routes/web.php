<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThesisController;

// Đường dẫn hiển thị danh sách đồ án (trang chủ)

Route::get('/', [ThesisController::class, 'index'])->name('theses.index');

// Đường dẫn để tạo mới một đồ án (hiển thị form thêm mới)
Route::get('/theses/create', [ThesisController::class, 'create'])->name('theses.create');

// Đường dẫn để lưu dữ liệu đồ án mới (thực hiện thêm mới)
Route::post('/theses', [ThesisController::class, 'store'])->name('theses.store');

// Đường dẫn để hiển thị chi tiết một đồ án cụ thể (tuỳ chọn)
Route::get('/theses/{id}', [ThesisController::class, 'show'])->name('theses.show');

// Đường dẫn để chỉnh sửa thông tin đồ án (hiển thị form chỉnh sửa)
Route::get('/theses/{id}/edit', [ThesisController::class, 'edit'])->name('theses.edit');

// Đường dẫn để cập nhật thông tin đồ án (thực hiện cập nhật)
Route::put('/theses/{id}', [ThesisController::class, 'update'])->name('theses.update');

// Đường dẫn để xóa đồ án (thực hiện xóa sau khi có modal xác nhận)
Route::delete('/theses/{id}', [ThesisController::class, 'destroy'])->name('theses.destroy');
