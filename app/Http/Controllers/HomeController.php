<?php

namespace App\Http\Controllers;

use App\Clases\Empleados;
use App\Http\Requests;
use App\Empleado;
use App\Plantel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empleado = Empleado::find(Auth::user()->empleado_id);
        $plantel = Plantel::find($empleado->cct_plantel);
        return view('dashboard', compact('plantel'));
    }




}
