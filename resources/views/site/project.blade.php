@extends('layouts.public')

@section('pageTitle', $project->title . ' — ' . $siteTitle)

@section('content')
    <section class="section project-detail">
        <div class="container">
            <a class="back-link reveal" href="{{ route('projects') }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 11H7.8l5.6-5.6L12 4l-8 8 8 8 1.4-1.4L7.8 13H20v-2Z"/></svg>
                All projects
            </a>

            <div class="project-detail-hero">
                <div class="project-detail-copy reveal">
                    <p class="eyebrow">{{ $project->category ?: 'Project' }}</p>
                    <h1>{{ $project->title }}</h1>
                    <p class="lead project-detail-summary">{{ $project->summary }}</p>

                    @if ($project->live_url || $project->repo_url)
                        <div class="cta-row">
                            @if ($project->live_url)
                                <a class="btn btn-primary" href="{{ $project->live_url }}" target="_blank" rel="noreferrer">
                                    View live site
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 4h6v6h-2V6.4l-8.3 8.3-1.4-1.4L16.6 5H14V4Zm-9 3h4v2H6v10h10v-3h2v5H4V7h1Z"/></svg>
                                </a>
                            @endif

                            @if ($project->repo_url)
                                <a class="btn btn-ghost" href="{{ $project->repo_url }}" target="_blank" rel="noreferrer">
                                    Source code
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9.4 16.6-4.6-4.6 4.6-4.6L8 6 2 12l6 6 1.4-1.4Zm5.2 0 4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4Z"/></svg>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="project-detail-media reveal">
                    <div class="project-detail-thumb thumb-{{ (($project->id ?? 1) % 3) + 1 }}">
                        @if ($project->image)
                            <img src="{{ $project->image }}" alt="{{ $project->title }}">
                        @else
                            <span class="thumb-glyph">{{ str($project->title)->substr(0, 1)->upper() }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="project-meta">
                <div class="project-meta-card reveal">
                    <h2>Category</h2>
                    <p>{{ $project->category ?: 'Project' }}</p>
                </div>

                <div class="project-meta-card reveal">
                    <h2>Stack</h2>
                    @if ($project->tags)
                        <ul class="tag-list">
                            @foreach ($project->tags as $tag)
                                <li>{{ $tag }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="meta-muted">Not specified yet.</p>
                    @endif
                </div>

                <div class="project-meta-card reveal">
                    <h2>Links</h2>
                    <div class="project-meta-links">
                        @if ($project->live_url)
                            <a href="{{ $project->live_url }}" target="_blank" rel="noreferrer">Live site <span aria-hidden="true">↗</span></a>
                        @endif

                        @if ($project->repo_url)
                            <a href="{{ $project->repo_url }}" target="_blank" rel="noreferrer">Source code <span aria-hidden="true">↗</span></a>
                        @endif

                        @unless ($project->live_url || $project->repo_url)
                            <p class="meta-muted">No links provided.</p>
                        @endunless
                    </div>
                </div>
            </div>

            @if (filled($project->description))
                <div class="project-description reveal">
                    <div class="markdown">{!! $project->description_html !!}</div>
                </div>
            @endif
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="section related-projects">
            <div class="container">
                <div class="section-head reveal">
                    <div class="section-heading left">
                        <p class="eyebrow">Keep exploring</p>
                        <h2>Similar projects</h2>
                    </div>
                </div>

                <div class="projects-grid">
                    @foreach ($related as $relatedProject)
                        @include('site.partials.project-card', ['project' => $relatedProject])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
