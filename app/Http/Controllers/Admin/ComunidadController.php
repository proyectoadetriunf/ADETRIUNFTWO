<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ComunidadController extends Controller
{
    public function index()
    {
        return view('admin.comunidades.index');
    }
}
