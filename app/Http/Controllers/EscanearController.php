<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EscanearController extends Controller
{
    public function index()
    {
        return view('escanear');
    }
}
