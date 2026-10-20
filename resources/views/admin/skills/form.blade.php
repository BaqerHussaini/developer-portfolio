@extends('layouts.admin')

@section('title', $skill->exists ? 'Edit skill' : 'New skill')

@section('content')
    <form method="POST" action="{{ $skill->exists ? route('admin.skills.update', $skill) : route('admin.skills.store') }}">
        @csrf
        @isset($skill->id) @method('PUT') @endisset

        <div class="panel">
            <div class="panel-head">
                <h2>{{ $skill->exists ? 'Edit skill' : 'New skill' }}</h2>
                <a class="btn btn-outline btn-sm spacer" href="{{ route('admin.skills.index') }}">Back</a>
            </div>

            <div class="form-grid">
                <div class="cols-3">
                    <label class="field">
                        <span>Name</span>
                        <input type="text" name="name" value="{{ old('name', $skill->name) }}" required>
                    </label>

                    <label class="field">
                        <span>Category</span>
                        <input type="text" name="category" value="{{ old('category', $skill->category) }}" placeholder="Frontend">
                    </label>

                    <label class="field">
                        <span>Sort order</span>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $skill->sort_order ?? 0) }}" min="0">
                    </label>
                </div>

                <label class="field">
                    <span>Level (0–100)</span>
                    <input type="number" name="level" value="{{ old('level', $skill->level ?? 85) }}" min="0" max="100" required>
                </label>

                <label class="field check">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $skill->is_published))>
                    <span>Published</span>
                </label>
            </div>

            <div style="margin-top:1.2rem;display:flex;gap:.6rem">
                <button class="btn btn-primary" type="submit">{{ $skill->exists ? 'Save changes' : 'Add skill' }}</button>
                <a class="btn btn-outline" href="{{ route('admin.skills.index') }}">Cancel</a>
            </div>
        </div>
    </form>
@endsection
