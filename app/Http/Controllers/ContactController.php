<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function store(ContactRequest $request): RedirectResponse|JsonResponse
    {
        Message::create([
            ...$request->validated(),
            'ip_address' => $request->ip(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Thanks! Your message has been sent.',
            ]);
        }

        return redirect()->route('contact')->with('status', 'Thanks! Your message has been sent.');
    }
}
