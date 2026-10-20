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
                    <a class="btn btn-primary" href="#projects">{{ $hero['ctaPrimary'] }}</a>
                    <a class="btn btn-ghost" href="#contact">{{ $hero['ctaSecondary'] }}</a>
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

        <div class="scroll-hint" aria-hidden="true"><span></span></div>
    </section>

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

    <section id="about" class="section">
        <div class="container about-grid">
            <div class="about-copy reveal">
                <div class="section-heading left">
                    <p class="eyebrow">{{ $about['eyebrow'] }}</p>
                    <h2>{{ $about['headline'] }}</h2>
                </div>

                @foreach ($about['paragraphs'] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>

            <div class="skills-panel reveal">
                <h3 class="panel-title">Toolbox</h3>

                @forelse ($skills as $skill)
                    <div class="skill-row">
                        <div class="skill-head">
                            <span>{{ $skill->name }}</span>
                            <em>{{ $skill->level }}%</em>
                        </div>
                        <div class="skill-bar">
                            <i data-level="{{ $skill->level }}" style="--level: {{ $skill->level }}%"></i>
                        </div>
                    </div>
                @empty
                    <p class="empty-note">No skills added yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section id="contact" class="section contact-section">
        <div class="container contact-grid">
            <div class="contact-copy reveal">
                <div class="section-heading left">
                    <p class="eyebrow">{{ $contact['eyebrow'] }}</p>
                    <h2>{{ $contact['heading'] }}</h2>
                </div>

                <p class="lead">{{ $contact['intro'] }}</p>

                <ul class="contact-list">
                    <li>
                        <span class="contact-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Zm8 7.2 8-5V6l-8 5-8-5v1.2l8 5Z"/></svg></span>
                        <a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a>
                    </li>
                    @if ($contact['location'])
                        <li>
                            <span class="contact-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a7 7 0 0 1 7 7c0 5-7 13-7 13S5 14 5 9a7 7 0 0 1 7-7Zm0 4.5A2.5 2.5 0 1 0 12 11a2.5 2.5 0 0 0 0-4.5Z"/></svg></span>
                            <span>{{ $contact['location'] }}</span>
                        </li>
                    @endif
                </ul>

                @if ($contact['socials'])
                    <div class="socials">
                        @foreach ($contact['socials'] as $social)
                            <a href="{{ $social['url'] }}" target="_blank" rel="noreferrer" title="{{ $social['platform'] }}">
                                {{ str($social['platform'] ?? 'Link')->substr(0, 2)->upper() }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <form class="contact-form card reveal" action="{{ route('contact.store') }}" method="POST" novalidate>
                @csrf

                @if (session('status'))
                    <p class="form-alert success">{{ session('status') }}</p>
                @endif

                @if ($errors->any())
                    <p class="form-alert error">Please check the highlighted fields and try again.</p>
                @endif

                <div class="field-row">
                    <label class="field">
                        <span>Name</span>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Your name" required>
                        @error('name')<em class="field-error">{{ $message }}</em>@enderror
                    </label>

                    <label class="field">
                        <span>Email</span>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                        @error('email')<em class="field-error">{{ $message }}</em>@enderror
                    </label>
                </div>

                <label class="field">
                    <span>Subject</span>
                    <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Project inquiry">
                    @error('subject')<em class="field-error">{{ $message }}</em>@enderror
                </label>

                <label class="field">
                    <span>Message</span>
                    <textarea name="body" rows="5" placeholder="Tell me about your project" required>{{ old('body') }}</textarea>
                    @error('body')<em class="field-error">{{ $message }}</em>@enderror
                </label>

                <label class="honeypot" aria-hidden="true">
                    <span>Website</span>
                    <input type="text" name="website" tabindex="-1" autocomplete="off">
                </label>

                <button type="submit" class="btn btn-primary">
                    Send message
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 11.5 21 4l-7.5 17-2.2-6.3L4 11.5Z"/></svg>
                </button>
            </form>
        </div>
    </section>
@endsection
