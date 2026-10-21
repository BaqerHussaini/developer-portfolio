@extends('layouts.admin')

@section('title', 'Account')

@section('content')
    <form method="POST" action="{{ route('admin.account.update') }}">
        @csrf
        @method('PUT')

        <div class="panel">
            <div class="panel-head">
                <h2>Profile</h2>
            </div>

            <div class="form-grid">
                <label class="field">
                    <span>Full name</span>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" autocomplete="name" required>
                    @error('name')<small class="error-text">{{ $message }}</small>@enderror
                </label>

                <label class="field">
                    <span>Email address</span>
                    <input type="text" class="password-mask" value="{{ $user->maskedEmail() }}" readonly tabindex="-1" aria-label="Email address hidden for privacy">
                </label>

                <label class="field">
                    <span>Password</span>
                    <input type="text" class="password-mask" value="••••••••••" readonly tabindex="-1" aria-hidden="true">
                </label>
            </div>

            <div style="margin-top:1.2rem;display:flex;gap:.6rem;flex-wrap:wrap">
                <a class="btn btn-outline btn-sm" href="{{ route('admin.account.email.edit') }}">Change email</a>
                <a class="btn btn-outline btn-sm" href="{{ route('admin.account.password.edit') }}">Change password</a>
            </div>
        </div>

        <div style="margin-top:1.2rem;display:flex;gap:.6rem">
            <button class="btn btn-primary" type="submit">Save changes</button>
        </div>
    </form>
@endsection
