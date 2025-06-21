<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class CitaController extends Controller
{
    public function index()
    {
        return view('admin.citas.index');
    }
}
