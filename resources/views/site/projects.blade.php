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
                    @include('site.partials.project-card', ['project' => $project])
                @empty
                    <p class="empty-note">No projects published yet.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
