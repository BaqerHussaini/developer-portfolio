<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\Images;
use App\Support\SettingsSchema;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit(): View
    {
        $values = Setting::query()->get()->keyBy('key');

        return view('admin.settings', [
            'groups' => SettingsSchema::fields(),
            'values' => $values,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:4096'],
        ]);

        $data = $request->input('settings', []);
        $defaults = Setting::defaults();
        $currentValues = Setting::query()->get()->keyBy('key');
        $changed = false;

        foreach (SettingsSchema::keys() as $key) {
            if ($key === 'profile_photo') {
                continue;
            }

            if (! array_key_exists($key, $data)) {
                continue;
            }

            $raw = $data[$key];
            $type = SettingsSchema::field($key)['type'] ?? 'text';

            $value = match (true) {
                $raw === null => null,
                is_array($raw) => $this->cleanArray($raw),
                $type === 'lines' => $this->toLines($raw),
                $type === 'paragraphs' => $this->toParagraphs($raw),
                default => is_string($raw) ? trim($raw) : $raw,
            };

            $current = $currentValues->get($key)?->value ?? ($defaults[$key]['value'] ?? null);

            if ($current == $value) {
                continue;
            }

            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'group' => explode('_', $key)[0],
                    'value' => $value,
                ]
            );

            $changed = true;
        }

        if ($this->updateProfilePhoto($request)) {
            $changed = true;
        }

        return $changed
            ? back()->with('status', 'Settings saved.')
            : back()->with('status', 'Nothing updated.');
    }

    private function updateProfilePhoto(Request $request): bool
    {
        $current = Setting::get('profile_photo');
        $changed = false;

        if ($request->boolean('remove_photo') && $current) {
            Images::delete($current);
            $current = null;
            $changed = true;
        }

        if ($request->hasFile('photo')) {
            $current = Images::replace($request->file('photo'), $current, 'profile');
            $changed = true;
        }

        if ($changed) {
            Setting::updateOrCreate(
                ['key' => 'profile_photo'],
                ['group' => 'hero', 'value' => $current]
            );
        }

        return $changed;
    }

    private function toLines(string $raw): array
    {
        return collect(preg_split('/\R/u', $raw) ?: [])
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    private function toParagraphs(string $raw): array
    {
        return collect(preg_split('/\R\s*\R/u', $raw) ?: [])
            ->map(fn ($paragraph) => trim(preg_replace('/\s*\R\s*/u', ' ', $paragraph) ?? ''))
            ->filter()
            ->values()
            ->all();
    }

    private function cleanArray(array $value): array
    {
        $clean = [];

        foreach ($value as $entry) {
            if (! is_array($entry)) {
                if (is_string($entry) && trim($entry) !== '') {
                    $clean[] = trim($entry);
                }

                continue;
            }

            $entry = array_map(fn ($item) => is_string($item) ? trim($item) : $item, $entry);
            $entry = array_filter($entry, fn ($item) => $item !== '' && $item !== null);

            if ($entry !== []) {
                $clean[] = $entry;
            }
        }

        return $clean;
    }
}
