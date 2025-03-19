<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class VisitantesController extends Controller
{
    public function index()
    {
        return view('visitantes');
    }
}