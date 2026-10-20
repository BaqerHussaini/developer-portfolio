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

    public function projects(): View
    {
        return view('site.projects', Portfolio::content());
    }

    public function experience(): View
    {
        return view('site.experience', Portfolio::content());
    }

    public function about(): View
    {
        return view('site.about', Portfolio::content());
    }

    public function contact(): View
    {
        return view('site.contact', Portfolio::content());
    }
}
