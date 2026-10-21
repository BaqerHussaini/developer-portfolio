<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\Message;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Stat;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'counts' => [
                'projects' => Project::count(),
                'experiences' => Experience::count(),
                'skills' => Skill::count(),
                'stats' => Stat::count(),
                'messages' => Message::inbox()->count(),
                'unread' => Message::inbox()->unread()->count(),
            ],
            'latestMessages' => Message::inbox()->latest()->take(5)->get(),
            'latestProjects' => Project::latest()->take(5)->get(),
        ]);
    }

    public function heartbeat(): Response
    {
        return response()->noContent();
    }
}
