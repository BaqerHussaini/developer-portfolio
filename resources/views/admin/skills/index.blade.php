@extends('layouts.admin')

@section('title', 'Skills')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <h2>Skills</h2>
            <a class="btn btn-primary btn-sm spacer" href="{{ route('admin.skills.create') }}">New skill</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th style="width:200px">Level</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($skills as $skill)
                        <tr>
                            <td class="muted">{{ $skill->sort_order }}</td>
                            <td><strong>{{ $skill->name }}</strong></td>
                            <td class="muted">{{ $skill->category }}</td>
                            <td>
                                <div class="skill-bar" style="height:8px;border-radius:99px;background:rgba(255,255,255,.08);overflow:hidden">
                                    <i style="display:block;height:100%;width:{{ $skill->level }}%;background:linear-gradient(90deg,#6366f1,#22d3ee)"></i>
                                </div>
                                <span class="muted" style="font-size:.78rem">{{ $skill->level }}%</span>
                            </td>
                            <td><span class="badge {{ $skill->is_published ? 'on' : 'off' }}">{{ $skill->is_published ? 'Published' : 'Draft' }}</span></td>
                            <td class="actions">
                                <a class="btn btn-outline btn-sm" href="{{ route('admin.skills.edit', $skill) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.skills.destroy', $skill) }}"
                                      data-confirm="Delete “{{ $skill->name }}”?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty">No skills yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
