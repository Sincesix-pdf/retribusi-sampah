<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index()
    {
        return view('log-aktivitas.index');
    }
}
