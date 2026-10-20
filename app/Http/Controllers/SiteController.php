<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Support\Portfolio;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

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

    public function project(Project $project): View
    {
        abort_unless($project->is_published, 404);

        $content = Portfolio::content();
        $content['seoDescription'] = Str::limit(strip_tags($project->summary), 155);

        $related = collect();

        if (filled($project->category)) {
            $related = Project::published()
                ->whereKeyNot($project->getKey())
                ->where('category', $project->category)
                ->ordered()
                ->limit(3)
                ->get();
        }

        return view('site.project', [
            ...$content,
            'project' => $project,
            'related' => $related,
        ]);
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
