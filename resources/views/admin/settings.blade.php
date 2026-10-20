@extends('layouts.admin')

@section('title', 'Site content')

@section('content')
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @error('photo')<p class="flash error">{{ $message }}</p>@enderror

        <div class="tabs">
            @foreach ($groups as $group)
                <button type="button" data-tab="{{ $group['group'] }}">{{ $group['group_label'] }}</button>
            @endforeach
        </div>

        @foreach ($groups as $group)
            <div class="panel tab-panel" data-panel="{{ $group['group'] }}">
                @foreach ($group['fields'] as $field)
                    @php
                        $name = 'settings['.$field['key'].']';
                        $stored = $values->get($field['key'])?->value;
                        $fallback = \App\Models\Setting::defaults()[$field['key']]['value'] ?? '';
                        $value = $stored ?? $fallback;
                    @endphp

                    <div class="field" style="margin-bottom:1.1rem">
                        <span>{{ $field['label'] }}</span>

                        @php $type = $field['type']; @endphp

                        @if ($type === 'textarea')
                            <textarea name="{{ $name }}" rows="3">{{ is_array($value) ? implode("\n\n", $value) : $value }}</textarea>
                        @elseif ($type === 'paragraphs')
                            <textarea name="{{ $name }}" rows="6">{{ is_array($value) ? implode("\n\n", $value) : $value }}</textarea>
                        @elseif ($type === 'lines')
                            <textarea name="{{ $name }}" rows="4">{{ is_array($value) ? implode("\n", $value) : $value }}</textarea>
                        @elseif ($type === 'color')
                            <input type="color" name="{{ $name }}" value="{{ $value }}">
                        @elseif ($type === 'image')
                            @php $photoUrl = \App\Support\Images::url($value); @endphp

                            <div class="image-field">
                                <div class="image-preview round">
                                    @if ($photoUrl)
                                        <img src="{{ $photoUrl }}" alt="Profile photo">
                                    @else
                                        <span class="image-empty">No photo</span>
                                    @endif
                                </div>

                                <input type="file" name="photo" id="profilePhoto" accept="image/*">

                                <div class="image-actions">
                                    <button type="button" class="btn btn-outline btn-sm" data-preview="profilePhoto">Preview</button>

                                    @if ($photoUrl)
                                        <label class="field check">
                                            <input type="checkbox" name="remove_photo" value="1">
                                            <span>Delete current photo</span>
                                        </label>
                                    @endif
                                </div>

                                <small>Leave empty to keep showing your initials ({{ $values->get('logo_initials')?->value ?? 'BH' }}).</small>
                            </div>
                        @elseif (in_array($type, ['metrics', 'socials']))
                            @php
                                $repeaterId = 'repeater-'.$field['key'];
                                $subFields = $type === 'metrics'
                                    ? ['label', 'value']
                                    : ['platform', 'icon', 'url'];
                                $placeholders = [
                                    'label' => 'Label',
                                    'value' => 'Value',
                                    'platform' => 'Platform',
                                    'icon' => 'Icon class (optional)',
                                    'url' => 'https://',
                                ];
                            @endphp

                            <div class="repeater" data-repeater="{{ $repeaterId }}">
                                @forelse ((array) $value as $index => $item)
                                    <div class="repeater-row">
                                        @foreach ($subFields as $sub)
                                            <input type="{{ $sub === 'url' ? 'url' : 'text' }}"
                                                   name="{{ $name }}[{{ $index }}][{{ $sub }}]"
                                                   value="{{ is_array($item) ? ($item[$sub] ?? '') : '' }}"
                                                   placeholder="{{ $placeholders[$sub] }}">
                                        @endforeach
                                        @if ($type === 'metrics')<span></span>@endif
                                        <button type="button" class="row-remove" title="Remove this row">&times;</button>
                                    </div>
                                @empty
                                    <p class="repeater-empty">Nothing here yet — use the button below to add one.</p>
                                @endforelse
                            </div>

                            <template id="{{ $repeaterId }}">
                                <div class="repeater-row">
                                    @foreach ($subFields as $sub)
                                        <input type="{{ $sub === 'url' ? 'url' : 'text' }}"
                                               name="{{ $name }}[__INDEX__][{{ $sub }}]"
                                               value=""
                                               placeholder="{{ $placeholders[$sub] }}">
                                    @endforeach
                                    @if ($type === 'metrics')<span></span>@endif
                                    <button type="button" class="row-remove" title="Remove this row">&times;</button>
                                </div>
                            </template>

                            <div class="repeater-add">
                                <button type="button" class="btn btn-outline btn-sm" data-add="{{ $repeaterId }}">
                                    {{ $type === 'metrics' ? 'Add metric' : 'Add link' }}
                                </button>
                            </div>
                        @else
                            <input type="text" name="{{ $name }}" value="{{ is_array($value) ? '' : $value }}">
                        @endif

                        @if (! empty($field['hint']))
                            <small>{{ $field['hint'] }}</small>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save site content</button>
            <a class="btn btn-outline" href="{{ route('admin.dashboard') }}">Cancel</a>
        </div>
    </form>
@endsection
