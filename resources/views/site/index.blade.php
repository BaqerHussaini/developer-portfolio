@extends('layouts.public')

@section('content')
    <section id="home" class="hero section">
        <div class="container hero-grid">
            <div class="hero-copy reveal">
                <p class="eyebrow">
                    <span class="pulse-dot"></span>{{ $hero['eyebrow'] }}
                </p>

                <h1 class="hero-title">
                    {{ $hero['headline'] }}
                    <span class="accent-text rotator" id="typedText"
                          data-phrases="{{ json_encode($hero['phrases'] ?? []) }}">{{ $hero['phrases'][0] ?? '' }}</span>
                </h1>

                <p class="lead">{{ $hero['description'] }}</p>

                <div class="cta-row">
                    <a class="btn btn-primary" href="{{ route('projects') }}">{{ $hero['ctaPrimary'] }}</a>
                    <a class="btn btn-ghost" href="{{ route('contact') }}">{{ $hero['ctaSecondary'] }}</a>
                </div>

                @if ($stats->isNotEmpty())
                    <ul class="mini-stats">
                        @foreach ($stats as $stat)
                            <li class="reveal">
                                <strong data-count-to="{{ $stat->value }}">{{ $stat->value }}</strong>
                                <span>{{ $stat->label }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="hero-visual reveal">
                <div class="profile-card tilt">
                    <div class="profile-glow" aria-hidden="true"></div>

                    <div class="profile-badge">
                        <span class="status-dot"></span>{{ $hero['badge'] }}
                    </div>

                    <div class="avatar">
                        @if ($hero['photo'])
                            <img class="avatar-photo" src="{{ $hero['photo'] }}" alt="{{ $hero['role'] }}">
                        @else
                            <span class="avatar-initials">{{ $logoInitials }}</span>
                        @endif
                        <div class="avatar-ring" aria-hidden="true"></div>
                    </div>

                    <div class="profile-info">
                        <p class="label">Current role</p>
                        <h3>{{ $hero['role'] }}</h3>
                    </div>

                    @if ($hero['metrics'])
                        <div class="metrics-grid">
                            @foreach ($hero['metrics'] as $metric)
                                <div class="metric-box">
                                    <strong>{{ $metric['value'] ?? '' }}</strong>
                                    <span>{{ $metric['label'] ?? '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
