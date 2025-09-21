<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index()
    {
        return view('member.pages.deposit.index');
    }
    public function log()
    {
        return view('member.pages.deposit.log');
    }
}
