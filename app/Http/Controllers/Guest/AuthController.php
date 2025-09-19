<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function signIn()
    {
        return view('guest.pages.sign-in.index');
    }
    public function signUp()
    {
        return view('guest.pages.sign-up.index');
    }
}
