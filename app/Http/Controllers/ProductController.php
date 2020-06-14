<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        activatePage('backend.product.index');

        return view('home');
    }
}
