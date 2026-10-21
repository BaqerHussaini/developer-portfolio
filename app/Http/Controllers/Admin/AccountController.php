<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.account', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user->name = $validated['name'];

        if (! $user->isDirty()) {
            return back()->with('status', 'Nothing updated.');
        }

        $user->save();

        return back()->with('status', 'Account updated.');
    }

    public function editEmail(Request $request): View
    {
        return view('admin.account-email', [
            'user' => $request->user(),
        ]);
    }

    public function updateEmail(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        if ($validated['email'] === $user->email) {
            return back()->with('status', 'That is already your email address.');
        }

        $user->email = $validated['email'];
        $user->save();

        $request->session()->regenerate();

        return redirect()
            ->route('admin.account.edit')
            ->with('status', 'Email updated.');
    }

    public function editPassword(): View
    {
        return view('admin.account-password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        return redirect()
            ->route('admin.account.edit')
            ->with('status', 'Password updated.');
    }
}
