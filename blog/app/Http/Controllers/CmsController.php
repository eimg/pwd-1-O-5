<?php

namespace App\Http\Controllers;

class CmsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        return view("cms.index");
    }
}
