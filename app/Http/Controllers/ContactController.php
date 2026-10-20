<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function store(ContactRequest $request): RedirectResponse
    {
        Message::create([
            ...$request->validated(),
            'ip_address' => $request->ip(),
        ]);

        return back()->with('status', 'Thanks! Your message has been sent.');
    }
}
