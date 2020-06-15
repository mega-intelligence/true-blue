<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        activatePage('backend.product.index');

        return view('backend.product.index');
    }
}
