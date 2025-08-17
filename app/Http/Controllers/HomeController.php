<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function guestDashboard() {
        return view('guestDashboard');
    }
    public function dashboard() {
        return view('faculty.dashboard');
    }
}
