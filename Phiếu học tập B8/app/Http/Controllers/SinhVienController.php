<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SinhVien;

class SinhVienController extends Controller
{
    // Phương thức index() (SELECT)
    public function index()
    {
        $danhSachSV = SinhVien::all();
        return view('sinhvien.list', compact('danhSachSV'));
    }

    // Phương thức store() (INSERT)
    public function store(Request $request)
    {
        $data = $request->all();
        SinhVien::create($data);
        return redirect()->route('sinhvien.index');
    }
}