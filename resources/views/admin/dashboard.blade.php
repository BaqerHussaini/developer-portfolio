@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="grid-stats">
        @foreach ([
            'Projects' => $counts['projects'],
            'Experience' => $counts['experiences'],
            'Skills' => $counts['skills'],
            'Messages' => $counts['messages'],
        ] as $label => $value)
            <a class="stat-tile" href="{{ $label === 'Projects' ? route('admin.projects.index') : ($label === 'Experience' ? route('admin.experiences.index') : ($label === 'Skills' ? route('admin.skills.index') : route('admin.messages.index'))) }}">
                <strong>{{ $value }}</strong>
                <span>{{ $label }}</span>
            </a>
        @endforeach
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>Latest messages</h2>
            <a class="btn btn-outline btn-sm spacer" href="{{ route('admin.messages.index') }}">Open inbox</a>
        </div>

        <div class="message-list">
            @forelse ($latestMessages as $message)
                <div class="message-row {{ $message->is_read ? '' : 'unread' }}">
                    <header>
                        <strong>{{ $message->name }}</strong>
                        <span class="muted">{{ $message->email }}</span>
                        @unless ($message->is_read)<span class="badge unread">New</span>@endunless
                        <time>{{ $message->created_at->diffForHumans() }}</time>
                    </header>
                    <p>{{ str($message->subject ?: 'No subject') }} — {{ str($message->body)->limit(120) }}</p>
                </div>
            @empty
                <p class="empty">No messages yet.</p>
            @endforelse
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>Recently added projects</h2>
            <a class="btn btn-outline btn-sm spacer" href="{{ route('admin.projects.index') }}">Manage projects</a>
        </div>

        <div class="message-list">
            @forelse ($latestProjects as $project)
                <div class="message-row">
                    <header>
                        <strong>{{ $project->title }}</strong>
                        <span class="badge {{ $project->is_published ? 'on' : 'off' }}">{{ $project->is_published ? 'Published' : 'Draft' }}</span>
                        <time>{{ $project->created_at->diffForHumans() }}</time>
                    </header>
                    <p>{{ $project->category }} — {{ str($project->summary)->limit(110) }}</p>
                </div>
            @empty
                <p class="empty">No projects yet.</p>
            @endforelse
        </div>
    </div>

    <div class="panel">
        <h2>Quick actions</h2>
        <div class="cta-row" style="display:flex;flex-wrap:wrap;gap:.6rem;margin:0">
            <a class="btn btn-outline" href="{{ route('admin.settings.edit') }}">Edit site text</a>
            <a class="btn btn-outline" href="{{ route('admin.projects.create') }}">New project</a>
            <a class="btn btn-outline" href="{{ route('admin.experiences.create') }}">New experience</a>
            <a class="btn btn-outline" href="{{ route('admin.skills.create') }}">New skill</a>
            <a class="btn btn-outline" href="{{ route('home') }}" target="_blank">View live site</a>
        </div>
    </div>
@endsection
