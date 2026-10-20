@extends('layouts.admin')

@section('title', $experience->exists ? 'Edit experience' : 'New experience')

@section('content')
    <form method="POST"
          action="{{ $experience->exists ? route('admin.experiences.update', $experience) : route('admin.experiences.store') }}">
        @csrf
        @isset($experience->id) @method('PUT') @endisset

        <div class="panel">
            <div class="panel-head">
                <h2>{{ $experience->exists ? 'Edit experience' : 'New experience' }}</h2>
                <a class="btn btn-outline btn-sm spacer" href="{{ route('admin.experiences.index') }}">Back</a>
            </div>

            <div class="form-grid">
                <div class="cols-2">
                    <label class="field">
                        <span>Role</span>
                        <input type="text" name="role" value="{{ old('role', $experience->role) }}" required>
                    </label>

                    <label class="field">
                        <span>Company</span>
                        <input type="text" name="company" value="{{ old('company', $experience->company) }}">
                    </label>
                </div>

                <div class="cols-3">
                    <label class="field">
                        <span>Start date</span>
                        <input type="date" name="start_date" value="{{ old('start_date', $experience->start_date?->format('Y-m-d')) }}">
                    </label>

                    <label class="field">
                        <span>End date</span>
                        <input type="date" name="end_date" value="{{ old('end_date', $experience->end_date?->format('Y-m-d')) }}">
                    </label>

                    <label class="field">
                        <span>Sort order</span>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $experience->sort_order ?? 0) }}" min="0">
                    </label>
                </div>

                <label class="field">
                    <span>Description</span>
                    <textarea name="description" rows="4" required>{{ old('description', $experience->description) }}</textarea>
                </label>

                <div class="cols-2">
                    <label class="field check">
                        <input type="checkbox" name="is_current" value="1" @checked(old('is_current', $experience->is_current))>
                        <span>Current role</span>
                    </label>

                    <label class="field check">
                        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $experience->is_published))>
                        <span>Published</span>
                    </label>
                </div>
            </div>

            <div style="margin-top:1.2rem;display:flex;gap:.6rem">
                <button class="btn btn-primary" type="submit">{{ $experience->exists ? 'Save changes' : 'Add experience' }}</button>
                <a class="btn btn-outline" href="{{ route('admin.experiences.index') }}">Cancel</a>
            </div>
        </div>
    </form>
@endsection
