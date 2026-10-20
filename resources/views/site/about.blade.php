@extends('layouts.public')

@section('pageTitle', 'About — ' . $siteTitle)

@section('content')
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
@endsection
