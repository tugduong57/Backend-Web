<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    //// TODO 8: Thêm phương thức này
    public function showHomepage()
    {
        // TODO 9: Thay vì echo, chúng ta 'return'
        return "Chào mừng bạn đến với PHT Chương 6 - Laravel!";
    }
}
