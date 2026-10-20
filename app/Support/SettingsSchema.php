<?php

namespace App\Support;

class SettingsSchema
{
    /**
     * Definition of every editable setting shown in the admin panel.
     *
     * type: text | textarea | lines | paragraphs | metrics | socials | image
     */
    public static function fields(): array
    {
        return [
            [
                'group' => 'general',
                'group_label' => 'General',
                'fields' => [
                    ['key' => 'site_title', 'label' => 'Site title', 'type' => 'text', 'hint' => 'Used in the browser tab title.'],
                    ['key' => 'logo_initials', 'label' => 'Logo initials', 'type' => 'text'],
                    ['key' => 'seo_description', 'label' => 'SEO description', 'type' => 'textarea'],
                    ['key' => 'footer_note', 'label' => 'Footer note', 'type' => 'text'],
                ],
            ],
            [
                'group' => 'theme',
                'group_label' => 'Theme',
                'fields' => [
                    ['key' => 'theme_accent', 'label' => 'Accent colour', 'type' => 'color', 'hint' => 'Primary highlight colour.'],
                    ['key' => 'theme_accent_alt', 'label' => 'Secondary accent colour', 'type' => 'color'],
                ],
            ],
            [
                'group' => 'hero',
                'group_label' => 'Hero',
                'fields' => [
                    ['key' => 'hero_eyebrow', 'label' => 'Greeting', 'type' => 'text'],
                    ['key' => 'hero_headline', 'label' => 'Headline', 'type' => 'text', 'hint' => 'The rotating phrase appears on the line below it.'],
                    ['key' => 'hero_phrases', 'label' => 'Rotating phrases', 'type' => 'lines', 'hint' => 'One phrase per line.'],
                    ['key' => 'hero_description', 'label' => 'Description', 'type' => 'textarea'],
                    ['key' => 'hero_role', 'label' => 'Current role', 'type' => 'text'],
                    ['key' => 'hero_badge', 'label' => 'Availability badge', 'type' => 'text'],
                    ['key' => 'hero_cta_primary', 'label' => 'Primary button label', 'type' => 'text'],
                    ['key' => 'hero_cta_secondary', 'label' => 'Secondary button label', 'type' => 'text'],
                    ['key' => 'profile_photo', 'label' => 'Profile photo', 'type' => 'image', 'hint' => 'Shown in the hero card instead of the initials.'],
                    ['key' => 'hero_metrics', 'label' => 'Profile card metrics', 'type' => 'metrics'],
                ],
            ],
            [
                'group' => 'projects',
                'group_label' => 'Projects section',
                'fields' => [
                    ['key' => 'projects_eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                    ['key' => 'projects_heading', 'label' => 'Heading', 'type' => 'text'],
                ],
            ],
            [
                'group' => 'experience',
                'group_label' => 'Experience section',
                'fields' => [
                    ['key' => 'experience_eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                    ['key' => 'experience_heading', 'label' => 'Heading', 'type' => 'text'],
                ],
            ],
            [
                'group' => 'about',
                'group_label' => 'About section',
                'fields' => [
                    ['key' => 'about_eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                    ['key' => 'about_headline', 'label' => 'Headline', 'type' => 'text'],
                    ['key' => 'about_paragraphs', 'label' => 'Paragraphs', 'type' => 'paragraphs', 'hint' => 'Separate paragraphs with a blank line.'],
                ],
            ],
            [
                'group' => 'contact',
                'group_label' => 'Contact section',
                'fields' => [
                    ['key' => 'contact_eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                    ['key' => 'contact_heading', 'label' => 'Heading', 'type' => 'text'],
                    ['key' => 'contact_intro', 'label' => 'Intro text', 'type' => 'textarea'],
                    ['key' => 'contact_email', 'label' => 'Email', 'type' => 'text'],
                    ['key' => 'contact_location', 'label' => 'Location', 'type' => 'text'],
                    ['key' => 'contact_socials', 'label' => 'Social links', 'type' => 'socials'],
                ],
            ],
        ];
    }

    public static function keys(): array
    {
        return array_merge(...array_map(
            fn (array $group) => array_column($group['fields'], 'key'),
            static::fields()
        ));
    }

    /**
     * The definition of a single setting, or null when the key is unknown.
     */
    public static function field(string $key): ?array
    {
        foreach (static::fields() as $group) {
            foreach ($group['fields'] as $field) {
                if ($field['key'] === $key) {
                    return $field;
                }
            }
        }

        return null;
    }
}
