<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $messages = Message::query()
            ->when($request->boolean('archived'), fn ($q) => $q->where('is_archived', true))
            ->when(! $request->boolean('archived'), fn ($q) => $q->where('is_archived', false))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.messages.index', [
            'messages' => $messages,
            'archived' => $request->boolean('archived'),
            'unreadCount' => Message::inbox()->unread()->count(),
            'archivedCount' => Message::where('is_archived', true)->count(),
        ]);
    }

    public function show(Message $message): View
    {
        $message->update(['is_read' => true]);

        return view('admin.messages.show', compact('message'));
    }

    public function update(Message $message): RedirectResponse
    {
        $message->update([
            'is_read' => ! $message->is_read,
            'is_archived' => ! $message->is_archived,
        ]);

        return back()->with('status', 'Message updated.');
    }

    public function destroy(Message $message): RedirectResponse
    {
        $message->delete();

        return redirect()
            ->route('admin.messages.index', array_filter(['archived' => request()->boolean('archived')]))
            ->with('status', 'Message deleted.');
    }
}
