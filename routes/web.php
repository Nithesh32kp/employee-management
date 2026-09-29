<?php

use App\Http\Controllers\EmployeeManagement\EmployeeManagementAdd;
use App\Http\Controllers\EmployeeManagement\Login;
use App\Http\Controllers\EmployeeManagement\Register;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [Login::class, 'index'])->name('login');
    Route::post('/', [Login::class, 'store'])->name('login.store');
    Route::get('/register', [Register::class, 'index'])->name('register');
    Route::post('/register', [Register::class, 'store'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [Login::class, 'destroy'])->name('logout');
    Route::get('/employees', [EmployeeManagementAdd::class, 'index'])->name('employees.index');
    Route::post('/employees', [EmployeeManagementAdd::class, 'store'])->name('employees.store');
    Route::put('/employees/{employee}', [EmployeeManagementAdd::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [EmployeeManagementAdd::class, 'destroy'])->name('employees.destroy');
    Route::get('employees/export', [EmployeeManagementAdd::class, 'export'])->name('employees.export');
    Route::post('employees/import', [EmployeeManagementAdd::class, 'import'])->name('employees.import');
});