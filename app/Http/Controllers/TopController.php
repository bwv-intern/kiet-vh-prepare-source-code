<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TopController extends Controller
{
    public function __construct() {
    }

    /**
     * Render index page
     * @param Request $request
     */
    public function index(Request $request) {
        return view('screens.top.index');
    }
}
