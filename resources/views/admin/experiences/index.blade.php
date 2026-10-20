@extends('layouts.admin')

@section('title', 'Experience')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <h2>Experience</h2>
            <a class="btn btn-primary btn-sm spacer" href="{{ route('admin.experiences.create') }}">New entry</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Role</th>
                        <th>Company</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($experiences as $experience)
                        <tr>
                            <td class="muted">{{ $experience->sort_order }}</td>
                            <td><strong>{{ $experience->role }}</strong></td>
                            <td class="muted">{{ $experience->company }}</td>
                            <td class="muted">{{ $experience->period }}</td>
                            <td><span class="badge {{ $experience->is_published ? 'on' : 'off' }}">{{ $experience->is_published ? 'Published' : 'Draft' }}</span></td>
                            <td class="actions">
                                <a class="btn btn-outline btn-sm" href="{{ route('admin.experiences.edit', $experience) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.experiences.destroy', $experience) }}"
                                      data-confirm="Delete this experience entry?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty">No experience entries yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
