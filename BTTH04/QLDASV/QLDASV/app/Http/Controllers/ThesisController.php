<?php

namespace App\Http\Controllers;

use App\Models\Thesis;
use App\Models\Student;
use Illuminate\Http\Request;

class ThesisController extends Controller
{
    // Hiển thị danh sách các đồ án KHÔNG PHÂN TRANG
    // public function index()
    // {
    //     $theses = Thesis::with('student')->get(); // Lấy dữ liệu đồ án và sinh viên liên quan
    //     return view('theses.index', compact('theses'));
    // }

    //Hiển thị danh sách các đồ án kiểu CÓ PHÂN TRANG dùng paginate
    public function index()
    {
        // Sử dụng paginate thay vì all()
        $theses = Thesis::with('student')->paginate(5); // Lấy 5 bản ghi mỗi trang
        return view('theses.index', compact('theses'));
    }

    // Hiển thị form tạo đồ án mới
    public function create()
    {
        $students = Student::all(); // Lấy danh sách sinh viên để chọn
        return view('theses.create', compact('students'));
    }

    // Lưu đồ án mới
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'student_id' => 'required',
            'program' => 'required|max:255',
            'supervisor' => 'required|max:100',
            'abstract' => 'required',
            'submission_date' => 'required|date',
            'defense_date' => 'required|date',
        ]);

        Thesis::create($request->all());

        return redirect()->route('theses.index')->with('success', 'Đồ án đã được thêm thành công!');
    }

    // Hiển thị form chỉnh sửa đồ án
    public function edit($id)
    {
        $thesis = Thesis::findOrFail($id);
        $students = Student::all();
        return view('theses.edit', compact('thesis', 'students'));
    }

    // Cập nhật thông tin đồ án
    public function update(Request $request, $id) {
        // Kiểm tra dữ liệu đầu vào (validation)
        $request->validate([
            'title' => 'required',
            'student_id' => 'required',
            'program' => 'required',
            'supervisor' => 'required',
            'submission_date' => 'nullable|date',
            'defense_date' => 'nullable|date',
        ]);
    
        // Tìm đối tượng Thesis cần cập nhật
        $thesis = Thesis::find($id);
        
        // Cập nhật thông tin
        $thesis->update($request->all());
    
        // Điều hướng trở lại trang index với thông báo thành công
        return redirect()->route('theses.index')->with('success', 'Đồ án được cập nhật thành công');
    }
    

    // Xóa đồ án
    public function destroy($id)
    {
        $thesis = Thesis::findOrFail($id);
        $thesis->delete();

        return redirect()->route('theses.index')->with('success', 'Đồ án đã được xóa thành công!');
    }
}
