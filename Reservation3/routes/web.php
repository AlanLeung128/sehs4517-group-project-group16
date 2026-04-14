<?php

use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

// Home Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Customer Routes - Reservation
Route::get('/reservation', [ReservationController::class, 'index'])
    ->name('reservation.index');

Route::post('/reservation', [ReservationController::class, 'store'])
    ->name('reservation.store');

// View Today's Reservations
Route::get('/view-today', [ReservationController::class, 'viewToday'])
    ->name('view.today');

// Thank You Page
Route::get('/thankyou', [ReservationController::class, 'thankyou'])
    ->name('thankyou');

// Staff Dashboard (Temporary - can be protected with middleware later)
Route::get('/staff/dashboard', function () {
    return view('dashboard');
})->name('staff.dashboard');

// Logout
Route::get('/logout', function () {
    // TODO: Implement proper authentication logout later
    return redirect('/')->with('success', 'You have been logged out successfully.');
})->name('logout');