<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{

    public function index()
    {
        activatePage('backend.warehouse.index');

        return view('backend.warehouse.index');
    }
}
