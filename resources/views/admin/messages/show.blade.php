@extends('layouts.admin')

@section('title', 'Message')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <h2>{{ $message->subject ?: 'No subject' }}</h2>
            <a class="btn btn-outline btn-sm spacer" href="{{ route('admin.messages.index') }}">Back to inbox</a>
        </div>

        <p class="muted">
            <strong style="color:var(--text)">{{ $message->name }}</strong>
            &lt;{{ $message->email }}&gt;
            &middot; {{ $message->created_at->format('d M Y, H:i') }}
            @if ($message->ip_address) &middot; IP {{ $message->ip_address }} @endif
        </p>

        <div class="message-body">{{ $message->body }}</div>

        <div style="display:flex;gap:.6rem;margin-top:1.2rem;flex-wrap:wrap">
            <a class="btn btn-primary" href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: '.($message->subject ?: 'Your message')) }}">Reply by email</a>

            <form method="POST" action="{{ route('admin.messages.update', $message) }}">
                @csrf
                @method('PATCH')
                <button class="btn btn-outline" type="submit">{{ $message->is_read ? 'Mark unread' : 'Mark read' }}</button>
            </form>

            <form method="POST" action="{{ route('admin.messages.update', $message) }}">
                @csrf
                @method('PATCH')
                <button class="btn btn-outline" type="submit">{{ $message->is_archived ? 'Restore to inbox' : 'Archive' }}</button>
            </form>

            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" data-confirm="Permanently delete this message?">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit">Delete</button>
            </form>
        </div>
    </div>
@endsection
