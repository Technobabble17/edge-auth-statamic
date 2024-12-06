<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberAuthController;
use App\Http\Middleware\MemberAuth;

// Member Authentication Routes
Route::get('/member/login', [MemberAuthController::class, 'showLogin'])->name('member.login');
Route::post('/member/login', [MemberAuthController::class, 'login'])->name('member.login.submit');
Route::get('/member/register', [MemberAuthController::class, 'showRegister'])->name('member.register');
Route::post('/member/register', [MemberAuthController::class, 'register'])->name('member.register.submit');
Route::post('/member/logout', [MemberAuthController::class, 'logout'])->name('member.logout');

// Protected Member Routes
Route::middleware(MemberAuth::class)->group(function () {
    Route::get('/members/dashboard', function () {
        return view('members.dashboard');
    })->name('member.dashboard');
});
