@extends('layouts.public')

@section('pageTitle', 'Projects — ' . $siteTitle)

@section('content')
    <section id="projects" class="section">
        <div class="container">
            <div class="section-heading reveal">
                <p class="eyebrow">{{ $projects['eyebrow'] }}</p>
                <h2>{{ $projects['heading'] }}</h2>
            </div>

            <div class="projects-grid">
                @forelse ($projects['items'] as $project)
                    <article class="project-card reveal">
                        <div class="project-thumb thumb-{{ ($loop->iteration % 3) + 1 }}">
                            @if ($project->image)
                                <img src="{{ $project->image }}" alt="{{ $project->title }}">
                            @else
                                <span class="thumb-label">{{ $project->category }}</span>
                                <span class="thumb-glyph">{{ str($project->title)->substr(0, 1)->upper() }}</span>
                            @endif
                        </div>

                        <div class="project-copy">
                            <div class="project-topline">
                                <h3>{{ $project->title }}</h3>
                                @if ($project->live_url)
                                    <a href="{{ $project->live_url }}" target="_blank" rel="noreferrer" aria-label="Open {{ $project->title }}">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 4h6v6h-2V6.4l-8.3 8.3-1.4-1.4L16.6 5H14V4Zm-9 3h4v2H6v10h10v-3h2v5H4V7h1Z"/></svg>
                                    </a>
                                @endif
                            </div>

                            <p>{{ $project->summary }}</p>

                            @if ($project->tags)
                                <ul class="tag-list">
                                    @foreach ($project->tags as $tag)
                                        <li>{{ $tag }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <div class="project-links">
                                @if ($project->repo_url)
                                    <a href="{{ $project->repo_url }}" target="_blank" rel="noreferrer">Source code</a>
                                @endif
                                @if ($project->is_featured)
                                    <span class="chip-featured">Featured</span>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="empty-note">No projects published yet.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
