<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BonusController extends Controller
{
    public function index()
    {
        return view('member.pages.bonus.index');
    }
}
