@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <h2>{{ $archived ? 'Archived messages' : 'Inbox' }}</h2>

            <div class="spacer" style="display:flex;gap:.5rem">
                <a class="btn btn-outline btn-sm {{ $archived ? '' : 'btn-primary' }}" href="{{ route('admin.messages.index') }}">
                    Inbox ({{ $unreadCount }} new)
                </a>
                <a class="btn btn-outline btn-sm {{ $archived ? 'btn-primary' : '' }}" href="{{ route('admin.messages.index', ['archived' => 1]) }}">
                    Archived ({{ $archivedCount }})
                </a>
            </div>
        </div>

        <div class="message-list">
            @forelse ($messages as $message)
                <a class="message-row {{ $message->is_read ? '' : 'unread' }}" href="{{ route('admin.messages.show', $message) }}">
                    <header>
                        <strong>{{ $message->name }}</strong>
                        <span class="muted">{{ $message->email }}</span>
                        @unless ($message->is_read)<span class="badge unread">New</span>@endunless
                        @if ($message->is_archived)<span class="badge archived">Archived</span>@endif
                        <time>{{ $message->created_at->format('d M Y, H:i') }}</time>
                    </header>
                    <p>{{ $message->subject ?: 'No subject' }} — {{ str($message->body)->limit(140) }}</p>
                </a>
            @empty
                <p class="empty">No {{ $archived ? 'archived' : '' }} messages.</p>
            @endforelse
        </div>

        @if ($messages->hasPages())
            <div style="margin-top:1rem">
                {{ $messages->onEachSide(1)->links('admin.partials.pagination') }}
            </div>
        @endif
    </div>
@endsection
