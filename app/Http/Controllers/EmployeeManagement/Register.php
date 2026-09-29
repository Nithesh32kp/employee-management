<?php

namespace App\Http\Controllers\EmployeeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Register extends Controller
{
    //

    public function index()
    {
        return view('register');
    }
}
