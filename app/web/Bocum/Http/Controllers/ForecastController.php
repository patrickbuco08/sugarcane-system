<?php

namespace Bocum\Http\Controllers;

use Illuminate\Http\Request;

class ForecastController extends Controller
{
    public function index()
    {
        return view('forecast.index');
    }
}
