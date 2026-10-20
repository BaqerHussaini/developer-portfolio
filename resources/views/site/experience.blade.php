@extends('layouts.public')

@section('pageTitle', 'Experience — ' . $siteTitle)

@section('content')
    <section id="experience" class="section muted-section">
        <div class="container experience-grid">
            <div class="section-heading left reveal">
                <p class="eyebrow">{{ $experience['eyebrow'] }}</p>
                <h2>{{ $experience['heading'] }}</h2>
                <p class="heading-note">Roles, teams and the problems I got to solve along the way.</p>
            </div>

            <div class="timeline">
                @forelse ($experience['items'] as $item)
                    <div class="timeline-item reveal">
                        <div class="timeline-marker" aria-hidden="true"><span></span></div>
                        <div class="timeline-card">
                            <span class="timeline-period">{{ $item->period }}</span>
                            <h3>{{ $item->role }}</h3>
                            @if ($item->company)
                                <p class="timeline-company">{{ $item->company }}</p>
                            @endif
                            <p>{{ $item->description }}</p>
                        </div>
                    </div>
                @empty
                    <p class="empty-note">No experience entries yet.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
