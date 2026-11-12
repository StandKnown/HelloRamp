<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function studio(): View
    {
        return view('marketing.products.studio');
    }

    public function social(): View
    {
        return view('marketing.products.social');
    }

    public function engage(): View
    {
        return view('marketing.products.engage');
    }
}
