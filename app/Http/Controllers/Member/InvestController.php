<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvestController extends Controller
{
    public function index()
    {
        return view('member.pages.invest.index');
    }
    public function log()
    {
        return view('member.pages.invest.log');
    }
}
