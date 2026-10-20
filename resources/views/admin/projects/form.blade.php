@extends('layouts.admin')

@section('title', $project->exists ? 'Edit project' : 'New project')

@section('content')
    <form method="POST"
          enctype="multipart/form-data"
          action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}">
        @csrf
        @isset($project->id) @method('PUT') @endisset

        <div class="panel">
            <div class="panel-head">
                <h2>{{ $project->exists ? 'Edit project' : 'New project' }}</h2>
                <a class="btn btn-outline btn-sm spacer" href="{{ route('admin.projects.index') }}">Back</a>
            </div>

            @error('image')<p class="flash error">{{ $message }}</p>@enderror

            <div class="form-grid">
                <div class="image-field">
                    <span class="image-label">Project image</span>

                    <div class="image-preview">
                        @if ($project->image)
                            <img src="{{ $project->image }}" alt="{{ $project->title }} image">
                        @else
                            <span class="image-empty">No image yet</span>
                        @endif
                    </div>

                    <input type="file" name="image" id="projectImage" accept="image/*">

                    <div class="image-actions">
                        <button type="button" class="btn btn-outline btn-sm" data-preview="projectImage">Preview</button>

                        @if ($project->image_path)
                            <label class="field check">
                                <input type="checkbox" name="remove_image" value="1">
                                <span>Delete current image</span>
                            </label>
                        @endif
                    </div>

                    <small>JPG, PNG, WebP or SVG up to 4 MB. Choosing a new file replaces the current one.</small>
                </div>

                <div class="cols-2">
                    <label class="field">
                        <span>Title</span>
                        <input type="text" name="title" value="{{ old('title', $project->title) }}" data-slug-source required>
                    </label>

                    <label class="field">
                        <span>Slug</span>
                        <input type="text" name="slug" value="{{ old('slug', $project->slug) }}" data-slug-target>
                        <small>Leave empty to generate from the title.</small>
                    </label>
                </div>

                <div class="cols-2">
                    <label class="field">
                        <span>Category</span>
                        <input type="text" name="category" value="{{ old('category', $project->category) }}" placeholder="Web App">
                    </label>

                    <label class="field">
                        <span>Tags</span>
                        <input type="text" name="tags" value="{{ old('tags', implode(', ', $project->tags ?? [])) }}" placeholder="React, Node.js, Dashboard">
                        <small>Separate tags with commas.</small>
                    </label>
                </div>

                <label class="field">
                    <span>Summary</span>
                    <textarea name="summary" rows="4" required>{{ old('summary', $project->summary) }}</textarea>
                </label>

                <label class="field">
                    <span>Description (Markdown)</span>
                    <textarea name="description" rows="16" class="markdown-input" placeholder="## Overview&#10;&#10;Write about the project, the problem, and the outcome.">{{ old('description', $project->description) }}</textarea>
                    <small>Optional. Markdown supports headings, bold, italic, links, lists, tables, code blocks, and more.</small>
                </label>

                <details class="markdown-help">
                    <summary>Markdown cheat sheet</summary>
                    <pre># Heading 1
## Heading 2
### Heading 3

**Bold** and *italic* and ~~strikethrough~~

- Bullet list
1. Numbered list
- [ ] Task
- [x] Done

[Link text](https://example.com)

| Column A | Column B |
| -------- | -------- |
| Value 1  | Value 2  |

`inline code`

```php
echo 'Code block';
```

> Blockquote

---</pre>
                </details>

                <div class="cols-2">
                    <label class="field">
                        <span>Live URL</span>
                        <input type="url" name="live_url" value="{{ old('live_url', $project->live_url) }}">
                    </label>

                    <label class="field">
                        <span>Repository URL</span>
                        <input type="url" name="repo_url" value="{{ old('repo_url', $project->repo_url) }}">
                    </label>
                </div>

                <div class="cols-3">
                    <label class="field">
                        <span>Sort order</span>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $project->sort_order ?? 0) }}" min="0">
                    </label>

                    <label class="field check">
                        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $project->is_published))>
                        <span>Published</span>
                    </label>

                    <label class="field check">
                        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $project->is_featured))>
                        <span>Show on home page</span>
                    </label>
                </div>
            </div>

            <div style="margin-top:1.2rem;display:flex;gap:.6rem">
                <button class="btn btn-primary" type="submit">{{ $project->exists ? 'Save changes' : 'Create project' }}</button>
                <a class="btn btn-outline" href="{{ route('admin.projects.index') }}">Cancel</a>
            </div>
        </div>
    </form>
@endsection
