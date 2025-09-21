<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    public function index()
    {
        return view('member.pages.withdraw.index');
    }
    public function log()
    {
        return view('member.pages.withdraw.log');
    }
}
