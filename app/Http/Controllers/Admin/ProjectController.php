<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectRequest;
use App\Models\Project;
use App\Support\Images;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('admin.projects.index', [
            'projects' => Project::ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.form', [
            'project' => new Project(['is_published' => true, 'is_featured' => false]),
        ]);
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['image', 'remove_image']);

        $data['image_path'] = Images::replace($request->file('image'), null, 'projects');

        Project::create($data);

        return redirect()->route('admin.projects.index')->with('status', 'Project created.');
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.form', compact('project'));
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        $data = $request->safe()->except(['image', 'remove_image']);
        $current = $project->image_path;

        if ($request->boolean('remove_image')) {
            Images::delete($current);
            $current = null;
        }

        $data['image_path'] = Images::replace($request->file('image'), $current, 'projects');

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('status', 'Project updated.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return back()->with('status', 'Project deleted.');
    }
}
