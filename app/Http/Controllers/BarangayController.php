<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarangayController extends Controller
{
    public function index()
    {
        return view('barangay.barangay');
    }
}
