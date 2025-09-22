<?php

namespace App\Http\Controllers\Member;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvestController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)
            ->orderBy('type')
            ->orderBy('price')
            ->get()
            ->groupBy('type');

        return view('member.pages.invest.index', compact('products'));
    }
    public function log()
    {
        return view('member.pages.invest.log');
    }
}
