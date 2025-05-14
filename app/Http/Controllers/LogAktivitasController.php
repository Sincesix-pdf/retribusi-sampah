<?php

namespace App\Http\Controllers;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index()
    {
        $logAktivitas = logAktivitas::with('pengguna.role')->get();
        return view('admin.log', compact('logAktivitas'));
    }
}
