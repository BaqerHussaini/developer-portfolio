@extends('layouts.admin')

@section('title', 'Hero stats')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <h2>Hero stats</h2>
            <a class="btn btn-primary btn-sm spacer" href="{{ route('admin.stats.create') }}">New stat</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Value</th>
                        <th>Label</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stats as $stat)
                        <tr>
                            <td class="muted">{{ $stat->sort_order }}</td>
                            <td><strong>{{ $stat->value }}</strong></td>
                            <td class="muted">{{ $stat->label }}</td>
                            <td><span class="badge {{ $stat->is_published ? 'on' : 'off' }}">{{ $stat->is_published ? 'Published' : 'Draft' }}</span></td>
                            <td class="actions">
                                <a class="btn btn-outline btn-sm" href="{{ route('admin.stats.edit', $stat) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.stats.destroy', $stat) }}"
                                      data-confirm="Delete “{{ $stat->label }}”?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty">No stats yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
