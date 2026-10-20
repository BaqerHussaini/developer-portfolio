<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StatRequest;
use App\Models\Stat;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class StatController extends Controller
{
    public function index(): View
    {
        return view('admin.stats.index', [
            'stats' => Stat::ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.stats.form', [
            'stat' => new Stat(['is_published' => true]),
        ]);
    }

    public function store(StatRequest $request): RedirectResponse
    {
        Stat::create($request->validated());

        return redirect()->route('admin.stats.index')->with('status', 'Stat added.');
    }

    public function edit(Stat $stat): View
    {
        return view('admin.stats.form', compact('stat'));
    }

    public function update(StatRequest $request, Stat $stat): RedirectResponse
    {
        $stat->fill($request->validated());

        if (! $stat->isDirty()) {
            return redirect()->route('admin.stats.index')->with('status', 'Nothing updated.');
        }

        $stat->save();

        return redirect()->route('admin.stats.index')->with('status', 'Stat updated.');
    }

    public function destroy(Stat $stat): RedirectResponse
    {
        $stat->delete();

        return back()->with('status', 'Stat deleted.');
    }
}
