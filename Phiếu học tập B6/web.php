<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController; // TODO 11: Import PageController
// TODO 12: Thêm 2 route này
Route::get('/', [PageController::class, 'showHomepage']);
Route::get('/about', [PageController::class, 'showHomepage']);