<?php

use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// 顧客頁面
Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation.index');
Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');
Route::get('/view-today', [ReservationController::class, 'viewToday'])->name('view.today');
Route::get('/thankyou', [ReservationController::class, 'thankyou'])->name('thankyou');

// 員工後台（暫時公開，之後可加 middleware 保護）
Route::get('/staff/dashboard', function () {
    return view('staff.dashboard');
})->name('staff.dashboard');

// 登出
Route::get('/logout', function () {
    // 之後可接真實 Auth
    return redirect('/')->with('success', '已登出');
})->name('logout');