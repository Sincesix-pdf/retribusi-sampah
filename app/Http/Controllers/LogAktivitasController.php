<?php

namespace App\Http\Controllers;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index()
    {
        $logAktivitas = logAktivitas::with('pengguna.role')->latest()->paginate(10);
        return view('admin.log', compact('logAktivitas'));
    }
}
