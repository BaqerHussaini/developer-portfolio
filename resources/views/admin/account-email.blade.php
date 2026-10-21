@extends('layouts.admin')

@section('title', 'Change email')

@section('content')
    <form method="POST" action="{{ route('admin.account.email.update') }}">
        @csrf
        @method('PUT')

        <div class="panel">
            <div class="panel-head">
                <h2>Change email</h2>
                <a class="btn btn-outline btn-sm spacer" href="{{ route('admin.account.edit') }}">Back</a>
            </div>

            <div class="form-grid">
                <label class="field">
                    <span>Current email</span>
                    <input type="text" class="password-mask" value="{{ $user->maskedEmail() }}" readonly tabindex="-1" aria-label="Current email hidden for privacy">
                </label>

                <label class="field">
                    <span>New email address</span>
                    <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                    @error('email')<small class="error-text">{{ $message }}</small>@enderror
                </label>

                <label class="field">
                    <span>Current password</span>
                    <input type="password" name="current_password" autocomplete="current-password" required>
                    @error('current_password')<small class="error-text">{{ $message }}</small>@enderror
                </label>
            </div>

            <div style="margin-top:1.2rem;display:flex;gap:.6rem">
                <button class="btn btn-primary" type="submit">Save email</button>
                <a class="btn btn-outline" href="{{ route('admin.account.edit') }}">Cancel</a>
            </div>
        </div>
    </form>
@endsection
