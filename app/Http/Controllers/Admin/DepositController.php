<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index()
    {
        return view('admin.pages.deposit.index');
    }
    public function edit()
    {
        return view('admin.pages.deposit.detail');
    }
}
