@extends('layouts.admin')

@section('title', $stat->exists ? 'Edit stat' : 'New stat')

@section('content')
    <form method="POST" action="{{ $stat->exists ? route('admin.stats.update', $stat) : route('admin.stats.store') }}">
        @csrf
        @isset($stat->id) @method('PUT') @endisset

        <div class="panel">
            <div class="panel-head">
                <h2>{{ $stat->exists ? 'Edit stat' : 'New stat' }}</h2>
                <a class="btn btn-outline btn-sm spacer" href="{{ route('admin.stats.index') }}">Back</a>
            </div>

            <div class="form-grid">
                <div class="cols-3">
                    <label class="field">
                        <span>Value</span>
                        <input type="text" name="value" value="{{ old('value', $stat->value) }}" placeholder="4+" required>
                    </label>

                    <label class="field">
                        <span>Label</span>
                        <input type="text" name="label" value="{{ old('label', $stat->label) }}" placeholder="Years Experience" required>
                    </label>

                    <label class="field">
                        <span>Sort order</span>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $stat->sort_order ?? 0) }}" min="0">
                    </label>
                </div>

                <label class="field check">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $stat->is_published))>
                    <span>Published</span>
                </label>
            </div>

            <div style="margin-top:1.2rem;display:flex;gap:.6rem">
                <button class="btn btn-primary" type="submit">{{ $stat->exists ? 'Save changes' : 'Add stat' }}</button>
                <a class="btn btn-outline" href="{{ route('admin.stats.index') }}">Cancel</a>
            </div>
        </div>
    </form>
@endsection
