<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
class ViewController extends Controller
{
    public function getLogin()
    {
        return view('login');
    }

    public function getRegister()
    {
        return view('register');
    }

    public function getDashboard()
    {
        return view('dashboard');
    }
}
