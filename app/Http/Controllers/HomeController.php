<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->user()->role=='admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('students.dashboard');
    }
}
