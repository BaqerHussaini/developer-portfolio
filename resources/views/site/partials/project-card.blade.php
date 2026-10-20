<article class="project-card reveal">
    <div class="project-thumb thumb-{{ (($project->id ?? $loop->iteration ?? 1) % 3) + 1 }}">
        @if ($project->image)
            <img src="{{ $project->image }}" alt="{{ $project->title }}">
        @else
            <span class="thumb-label">{{ $project->category }}</span>
            <span class="thumb-glyph">{{ str($project->title)->substr(0, 1)->upper() }}</span>
        @endif
    </div>

    <div class="project-copy">
        <div class="project-topline">
            <h3><a class="project-title-link" href="{{ route('projects.show', $project->slug) }}">{{ $project->title }}</a></h3>
            @if ($project->live_url)
                <a href="{{ $project->live_url }}" target="_blank" rel="noreferrer" aria-label="Open {{ $project->title }} live site">
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
