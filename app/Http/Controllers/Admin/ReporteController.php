<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ReporteController extends Controller
{
    public function avances()
    {
        return view('admin.reportes.avances');
    }

    public function financieros()
    {
        return view('admin.reportes.financieros');
    }

    public function comunidades()
    {
        return view('admin.reportes.comunidades');
    }
}
