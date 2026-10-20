<?php

namespace App\Support;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\Stat;

class Portfolio
{
    /**
     * All editable site content, pulled from the database.
     */
    public static function content(): array
    {
        $settings = Setting::query()->get()->keyBy('key');

        $value = static fn (string $key): mixed => $settings->has($key)
            ? $settings->get($key)->value
            : (Setting::defaults()[$key]['value'] ?? null);

        return [
            'siteTitle' => $value('site_title'),
            'logoInitials' => $value('logo_initials'),
            'seoDescription' => $value('seo_description'),
            'footerNote' => $value('footer_note'),
            'accent' => $value('theme_accent'),
            'accentAlt' => $value('theme_accent_alt'),

            'hero' => [
                'eyebrow' => $value('hero_eyebrow'),
                'headline' => $value('hero_headline'),
                'phrases' => static::lines($value('hero_phrases')),
                'description' => $value('hero_description'),
                'role' => $value('hero_role'),
                'badge' => $value('hero_badge'),
                'photo' => Images::url($value('profile_photo')),
                'ctaPrimary' => $value('hero_cta_primary'),
                'ctaSecondary' => $value('hero_cta_secondary'),
                'metrics' => (array) $value('hero_metrics'),
            ],

            'projects' => [
                'eyebrow' => $value('projects_eyebrow'),
                'heading' => $value('projects_heading'),
                'items' => Project::published()->ordered()->get(),
                'featured' => static::homeProjects(),
            ],

            'experience' => [
                'eyebrow' => $value('experience_eyebrow'),
                'heading' => $value('experience_heading'),
                'items' => Experience::published()->ordered()->get(),
            ],

            'about' => [
                'eyebrow' => $value('about_eyebrow'),
                'headline' => $value('about_headline'),
                'paragraphs' => static::paragraphs($value('about_paragraphs')),
            ],

            'skills' => Skill::published()->ordered()->get(),

            'profilePhoto' => Images::url(Setting::get('profile_photo')),

            'stats' => Stat::published()->ordered()->get(),

            'contact' => [
                'eyebrow' => $value('contact_eyebrow'),
                'heading' => $value('contact_heading'),
                'intro' => $value('contact_intro'),
                'email' => $value('contact_email'),
                'location' => $value('contact_location'),
                'socials' => array_values(array_filter(
                    (array) $value('contact_socials'),
                    fn ($social) => ! empty($social['url'] ?? null)
                )),
            ],
        ];
    }

    /**
     * Projects selected for the home page; falls back to the first published
     * projects when the admin has not highlighted any yet.
     */
    private static function homeProjects(): Collection
    {
        $featured = Project::published()->featured()->ordered()->limit(3)->get();

        return $featured->isNotEmpty()
            ? $featured
            : Project::published()->ordered()->limit(3)->get();
    }

    /**
     * Normalise a list setting into a clean array of non-empty lines.
     * Tolerates values that were previously stored as one newline-joined string.
     */
    private static function lines(mixed $value): array
    {
        return collect((array) $value)
            ->flatMap(fn ($item) => preg_split('/\R/u', (string) $item) ?: [])
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    private static function paragraphs(mixed $value): array
    {
        return collect((array) $value)
            ->flatMap(fn ($item) => preg_split('/\R\s*\R/u', (string) $item) ?: [])
            ->map(fn ($paragraph) => trim((string) preg_replace('/\s*\R\s*/u', ' ', $paragraph)))
            ->filter()
            ->values()
            ->all();
    }
}
