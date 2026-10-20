<?php

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\Stat;
use Illuminate\Database\Seeder;

class PortfolioContentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Setting::defaults() as $key => $definition) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['group' => $definition['group'], 'value' => $definition['value']]
            );
        }

        $projects = [
            [
                'title' => 'TaskFlow Pro',
                'category' => 'Web App',
                'summary' => 'A productivity dashboard helping teams manage roles, priorities, and progress in a single workflow.',
                'description' => "## Overview\n\nTaskFlow Pro gives teams one place to plan work and follow it through. I designed the data model, built the REST API, and delivered a responsive dashboard with role-based access.\n\n**Highlights**\n\n- Role-based access control\n- Real-time progress tracking\n- Responsive, keyboard-friendly UI\n\n## Outcome\n\nThe result is a faster planning cycle and clearer visibility across projects.",
                'tags' => ['React', 'Node.js', 'Dashboard'],
                'live_url' => 'https://example.com/taskflow',
                'repo_url' => 'https://github.com/example/taskflow',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Cartora',
                'category' => 'E-commerce',
                'summary' => 'A modern online store experience designed for conversion, product storytelling, and smooth checkout flows.',
                'description' => "## Overview\n\nCartora is a storefront built around product storytelling and a frictionless path to checkout. I handled the interface design and frontend implementation, with a focus on *speed* and *accessibility*.\n\n## Outcome\n\nThe new experience reduced drop-off and made catalog updates simple for the client.",
                'tags' => ['UI Design', 'Storefront', 'UX'],
                'live_url' => 'https://example.com/cartora',
                'repo_url' => 'https://github.com/example/cartora',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'InsightHub',
                'category' => 'Platform',
                'summary' => 'A reporting platform for performance analytics, dashboards, and business insights across multiple departments.',
                'description' => "## Overview\n\nInsightHub brings reporting from several departments into a single analytics workspace. I built the dashboard components and the data pipeline that keeps metrics current.\n\n| Area | Result |\n| --- | --- |\n| Data refresh | Every 5 minutes |\n| Reporting | Automated |\n| Access | Role-based |\n\n## Outcome\n\nTeams can now answer common business questions without waiting on manual reports.",
                'tags' => ['Analytics', 'Data', 'SaaS'],
                'live_url' => 'https://example.com/insighthub',
                'repo_url' => 'https://github.com/example/insighthub',
                'is_featured' => false,
                'sort_order' => 3,
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(['title' => $project['title']], $project);
        }

        $experiences = [
            [
                'company' => 'Independent / Freelance',
                'role' => 'Full-Stack Developer',
                'start_date' => '2023-01-01',
                'is_current' => true,
                'description' => 'Designing and delivering end-to-end web applications with clean architecture, responsive interfaces, and practical business logic.',
                'sort_order' => 1,
            ],
            [
                'company' => 'Product Studio',
                'role' => 'Frontend Developer',
                'start_date' => '2021-01-01',
                'end_date' => '2022-12-31',
                'is_current' => false,
                'description' => 'Built responsive user interfaces, optimized performance, and translated product needs into polished frontend experiences.',
                'sort_order' => 2,
            ],
            [
                'company' => 'Web Agency',
                'role' => 'Web Developer',
                'start_date' => '2019-01-01',
                'end_date' => '2020-12-31',
                'is_current' => false,
                'description' => 'Created marketing sites, landing pages, and small business web products focused on speed, usability, and conversion.',
                'sort_order' => 3,
            ],
        ];

        foreach ($experiences as $experience) {
            Experience::updateOrCreate(['role' => $experience['role'], 'company' => $experience['company']], $experience);
        }

        $skills = [
            ['name' => 'HTML & CSS', 'level' => 95, 'category' => 'Frontend'],
            ['name' => 'JavaScript', 'level' => 92, 'category' => 'Frontend'],
            ['name' => 'React', 'level' => 90, 'category' => 'Frontend'],
            ['name' => 'Node.js', 'level' => 88, 'category' => 'Backend'],
            ['name' => 'PHP & Laravel', 'level' => 86, 'category' => 'Backend'],
            ['name' => 'PostgreSQL', 'level' => 82, 'category' => 'Backend'],
            ['name' => 'UI/UX', 'level' => 93, 'category' => 'Design'],
        ];

        foreach ($skills as $skill) {
            Skill::updateOrCreate(['name' => $skill['name']], $skill);
        }

        $stats = [
            ['value' => '4+', 'label' => 'Years Experience', 'sort_order' => 1],
            ['value' => '18', 'label' => 'Projects built', 'sort_order' => 2],
            ['value' => '9', 'label' => 'Clients served', 'sort_order' => 3],
        ];

        foreach ($stats as $stat) {
            Stat::updateOrCreate(['label' => $stat['label']], $stat);
        }
    }
}
