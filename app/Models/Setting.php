<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'group', 'value'];

    protected $casts = [
        'value' => 'array',
    ];

    public static function defaults(): array
    {
        return [
            'site_title' => ['group' => 'general', 'value' => 'Baqer Hussaini | Portfolio'],
            'logo_initials' => ['group' => 'general', 'value' => 'BH'],
            'seo_description' => ['group' => 'general', 'value' => 'Personal portfolio showcasing projects, experience, skills, and contact information.'],
            'footer_note' => ['group' => 'general', 'value' => 'All rights reserved.'],

            'hero_eyebrow' => ['group' => 'hero', 'value' => "Hello, I'm Baqer Hussaini"],
            'hero_headline' => ['group' => 'hero', 'value' => 'I build'],
            'hero_phrases' => ['group' => 'hero', 'value' => ['digital experiences', 'modern products', 'clean systems', 'smart solutions']],
            'hero_description' => ['group' => 'hero', 'value' => 'Software engineer and product-minded developer crafting reliable, scalable, and user-friendly digital experiences.'],
            'hero_role' => ['group' => 'hero', 'value' => 'Full-Stack Developer'],
            'hero_badge' => ['group' => 'hero', 'value' => 'Available for work'],
            'hero_cta_primary' => ['group' => 'hero', 'value' => 'View Projects'],
            'hero_cta_secondary' => ['group' => 'hero', 'value' => 'Hire Me'],
            'profile_photo' => ['group' => 'hero', 'value' => null],
            'hero_metrics' => ['group' => 'hero', 'value' => [
                ['label' => 'Web', 'value' => '96%'],
                ['label' => 'APIs', 'value' => '92%'],
                ['label' => 'UI', 'value' => '94%'],
                ['label' => 'Quality', 'value' => '4.9/5'],
            ]],

            'projects_eyebrow' => ['group' => 'projects', 'value' => 'Featured Work'],
            'projects_heading' => ['group' => 'projects', 'value' => 'Projects'],

            'experience_eyebrow' => ['group' => 'experience', 'value' => 'Career Path'],
            'experience_heading' => ['group' => 'experience', 'value' => 'Experience'],

            'about_eyebrow' => ['group' => 'about', 'value' => 'About Me'],
            'about_headline' => ['group' => 'about', 'value' => 'Building thoughtful digital experiences.'],
            'about_paragraphs' => ['group' => 'about', 'value' => [
                'I am a software engineer focused on turning ideas into clear, practical products. I enjoy building interfaces, APIs, and systems that feel effortless for users and reliable for businesses.',
                'My work blends engineering discipline with design thinking, helping teams ship products that are fast, maintainable, and shaped around real user needs.',
            ]],

            'contact_eyebrow' => ['group' => 'contact', 'value' => "Let's Talk"],
            'contact_heading' => ['group' => 'contact', 'value' => 'Contact'],
            'contact_intro' => ['group' => 'contact', 'value' => 'Available for freelance work, product development, and collaboration opportunities.'],
            'contact_email' => ['group' => 'contact', 'value' => 'engineer.baqer.hussaini@gmail.com'],
            'contact_location' => ['group' => 'contact', 'value' => 'Kabul, Afghanistan'],
            'contact_socials' => ['group' => 'contact', 'value' => [
                ['platform' => 'GitHub', 'icon' => 'fa-brands fa-github', 'url' => 'https://github.com'],
                ['platform' => 'LinkedIn', 'icon' => 'fa-brands fa-linkedin-in', 'url' => 'https://linkedin.com'],
                ['platform' => 'Dribbble', 'icon' => 'fa-brands fa-dribbble', 'url' => 'https://dribbble.com'],
            ]],

            'theme_accent' => ['group' => 'theme', 'value' => '#6366f1'],
            'theme_accent_alt' => ['group' => 'theme', 'value' => '#22d3ee'],
        ];
    }

    public static function get(string $key, mixed $fallback = null): mixed
    {
        $row = static::query()->where('key', $key)->first();

        return $row ? $row->value : $fallback;
    }
}
