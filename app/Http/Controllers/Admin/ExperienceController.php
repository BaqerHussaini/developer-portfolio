<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExperienceRequest;
use App\Models\Experience;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ExperienceController extends Controller
{
    public function index(): View
    {
        return view('admin.experiences.index', [
            'experiences' => Experience::ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.experiences.form', [
            'experience' => new Experience(['is_published' => true, 'is_current' => false]),
        ]);
    }

    public function store(ExperienceRequest $request): RedirectResponse
    {
        Experience::create($request->validated());

        return redirect()->route('admin.experiences.index')->with('status', 'Experience added.');
    }

    public function edit(Experience $experience): View
    {
        return view('admin.experiences.form', compact('experience'));
    }

    public function update(ExperienceRequest $request, Experience $experience): RedirectResponse
    {
        $experience->fill($request->validated());

        if (! $experience->isDirty()) {
            return redirect()->route('admin.experiences.index')->with('status', 'Nothing updated.');
        }

        $experience->save();

        return redirect()->route('admin.experiences.index')->with('status', 'Experience updated.');
    }

    public function destroy(Experience $experience): RedirectResponse
    {
        $experience->delete();

        return back()->with('status', 'Experience deleted.');
    }
}
