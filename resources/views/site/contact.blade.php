@extends('layouts.public')

@section('pageTitle', 'Contact — ' . $siteTitle)

@section('content')
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
                            <a href="{{ $social['url'] }}" target="_blank" rel="noreferrer"
                               title="{{ $social['platform'] }}"
                               aria-label="{{ $social['platform'] ?? 'Social link' }}">
                                @if (! empty($social['icon']))
                                    <i class="{{ $social['icon'] }}" aria-hidden="true"></i>
                                @else
                                    {{ str($social['platform'] ?? 'Link')->substr(0, 2)->upper() }}
                                @endif
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
