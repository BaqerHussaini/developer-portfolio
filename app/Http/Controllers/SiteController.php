<?php

namespace App\Http\Controllers;

use App\Support\Portfolio;
use Illuminate\Contracts\View\View;

class SiteController extends Controller
{
    public function index(): View
    {
        return view('site.index', Portfolio::content());
    }
}
