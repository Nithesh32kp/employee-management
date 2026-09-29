<?php

use App\Http\Controllers\EmployeeManagement\EmployeeManagementAdd;
use App\Http\Controllers\EmployeeManagement\Login;
use App\Http\Controllers\EmployeeManagement\Register;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/', [Login::class, 'index'])->name('login');
        Route::post('/', [Login::class, 'store'])->name('login.store');
        Route::get('/register', [Register::class, 'index'])->name('register');
        Route::post('/register', [Register::class, 'store'])->name('register.store');
        Route::get('/employees', [EmployeeManagementAdd::class, 'index'])->name('employees.index');
        Route::post('/employees', [EmployeeManagementAdd::class, 'store'])->name('employees.store');
        Route::put('/employees/{employee}', [EmployeeManagementAdd::class, 'update'])->name('employees.update');
        Route::delete('/employees/{employee}', [EmployeeManagementAdd::class, 'destroy'])->name('employees.destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/add', [EmployeeManagementAdd::class, 'index'])->name('employee.add');
});