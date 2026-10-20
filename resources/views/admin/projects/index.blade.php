@extends('layouts.admin')

@section('title', 'Projects')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <h2>Projects</h2>
            <a class="btn btn-primary btn-sm spacer" href="{{ route('admin.projects.create') }}">New project</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Tags</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $project)
                        <tr>
                            <td class="muted">{{ $project->sort_order }}</td>
                            <td>
                                <strong>{{ $project->title }}</strong>
                                <div class="muted" style="font-size:.8rem">{{ str($project->summary)->limit(70) }}</div>
                            </td>
                            <td class="muted">{{ $project->category }}</td>
                            <td class="muted">{{ implode(', ', $project->tags ?? []) }}</td>
                            <td>
                                <span class="badge {{ $project->is_published ? 'on' : 'off' }}">
                                    {{ $project->is_published ? 'Published' : 'Draft' }}
                                </span>
                                @if ($project->is_featured)
                                    <span class="badge unread">Featured</span>
                                @endif
                            </td>
                            <td class="actions">
                                <a class="btn btn-outline btn-sm" href="{{ route('admin.projects.edit', $project) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.projects.destroy', $project) }}"
                                      data-confirm="Delete “{{ $project->title }}”?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty">No projects yet. Create your first one.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
