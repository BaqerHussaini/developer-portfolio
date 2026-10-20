@extends('layouts.admin')

@section('title', 'Change password')

@section('content')
    <form method="POST" action="{{ route('admin.account.password.update') }}">
        @csrf
        @method('PUT')

        <div class="panel">
            <div class="panel-head">
                <h2>Change password</h2>
                <a class="btn btn-outline btn-sm spacer" href="{{ route('admin.account.edit') }}">Back</a>
            </div>

            <div class="form-grid">
                <label class="field">
                    <span>Current password</span>
                    <input type="password" name="current_password" autocomplete="off" required>
                    @error('current_password')<small class="error-text">{{ $message }}</small>@enderror
                </label>

                <div class="cols-2">
                    <label class="field">
                        <span>New password</span>
                        <input type="password" name="password" autocomplete="new-password" required>
                        @error('password')<small class="error-text">{{ $message }}</small>@enderror
                    </label>

                    <label class="field">
                        <span>Confirm new password</span>
                        <input type="password" name="password_confirmation" autocomplete="new-password" required>
                    </label>
                </div>
            </div>

            <div style="margin-top:1.2rem;display:flex;gap:.6rem">
                <button class="btn btn-primary" type="submit">Save password</button>
                <a class="btn btn-outline" href="{{ route('admin.account.edit') }}">Cancel</a>
            </div>
        </div>
    </form>
@endsection
