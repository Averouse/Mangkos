<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MangkosController extends Controller
{
    public function index()
    {
        // Fungsi ini menyuruh Laravel mencari file bernama 'index.blade.php'
        return view('pages.landing');
    }
}