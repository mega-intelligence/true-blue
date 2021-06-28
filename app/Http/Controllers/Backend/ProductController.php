<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

class ProductController extends Controller
{

    public function index()
    {
        return view('backend.product.index');
    }
}
