<?php

namespace Tests\Feature;

use App\Models\Experience;
use App\Models\Message;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\Stat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\PortfolioContentSeeder::class);
    }

    public function test_each_page_renders_its_seeded_content(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Full-Stack Developer')
            ->assertSee('Projects built');

        $this->get('/projects')
            ->assertOk()
            ->assertSee('TaskFlow Pro');

        $this->get('/experience')
            ->assertOk()
            ->assertSee('Present');

        $this->get('/about')
            ->assertOk()
            ->assertSee('Toolbox');

        $this->get('/contact')
            ->assertOk()
            ->assertSee('Kabul, Afghanistan');
    }

    public function test_home_page_shows_only_featured_projects_with_explore_link(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('TaskFlow Pro')
            ->assertSee('Cartora')
            ->assertDontSee('InsightHub')
            ->assertSee('Explore all projects')
            ->assertSee(route('projects'), false);
    }

    public function test_home_page_falls_back_to_recent_projects_when_none_are_featured(): void
    {
        Project::query()->update(['is_featured' => false]);

        $this->get('/')
            ->assertOk()
            ->assertSee('TaskFlow Pro')
            ->assertSee('Cartora')
            ->assertSee('InsightHub');
    }

    public function test_navigation_links_point_to_each_page(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee(route('projects'), false)
            ->assertSee(route('experience'), false)
            ->assertSee(route('about'), false)
            ->assertSee(route('contact'), false);
    }

    public function test_home_page_uses_settings_from_database(): void
    {
        Setting::updateOrCreate(
            ['key' => 'site_title'],
            ['group' => 'general', 'value' => 'Custom Portfolio Title']
        );

        $this->get('/')->assertSee('Custom Portfolio Title');
    }

    public function test_unpublished_projects_and_skills_are_hidden(): void
    {
        Project::create([
            'title' => 'Hidden Project',
            'slug' => 'hidden-project',
            'summary' => 'Should not be visible.',
            'is_published' => false,
        ]);

        Skill::create(['name' => 'Hidden Skill', 'level' => 10, 'is_published' => false]);

        $this->get('/projects')
            ->assertOk()
            ->assertDontSee('Hidden Project');

        $this->get('/about')
            ->assertOk()
            ->assertDontSee('Hidden Skill');
    }

    public function test_project_detail_page_renders_a_published_project(): void
    {
        $project = Project::firstWhere('title', 'TaskFlow Pro');

        $this->get(route('projects.show', $project->slug))
            ->assertOk()
            ->assertSee($project->title)
            ->assertSee($project->summary)
            ->assertSee('Overview')
            ->assertSee('Outcome')
            ->assertSee('TaskFlow Pro gives teams one place')
            ->assertSee('faster planning cycle')
            ->assertSee('All projects');
    }

    public function test_project_page_shows_up_to_three_similar_published_projects(): void
    {
        $project = Project::create([
            'title' => 'Primary Project',
            'category' => 'Test Category',
            'summary' => 'The project being viewed.',
            'is_published' => true,
        ]);

        foreach (range(1, 4) as $index) {
            Project::create([
                'title' => "Similar Project {$index}",
                'category' => 'Test Category',
                'summary' => 'A related project.',
                'is_published' => true,
                'sort_order' => $index,
            ]);
        }

        Project::create([
            'title' => 'Other Category Project',
            'category' => 'Mobile',
            'summary' => 'Not related.',
            'is_published' => true,
        ]);

        Project::create([
            'title' => 'Unpublished Similar Project',
            'category' => 'Test Category',
            'summary' => 'Hidden similar project.',
            'is_published' => false,
        ]);

        $this->get(route('projects.show', $project->slug))
            ->assertOk()
            ->assertSee('Similar projects')
            ->assertSee('Similar Project 1')
            ->assertSee('Similar Project 2')
            ->assertSee('Similar Project 3')
            ->assertDontSee('Similar Project 4')
            ->assertDontSee('Unpublished Similar Project')
            ->assertDontSee('Other Category Project');
    }

    public function test_project_description_renders_markdown_and_strips_unsafe_html(): void
    {
        $withoutDescription = Project::create([
            'title' => 'Plain Project',
            'summary' => 'No detail yet.',
            'is_published' => true,
        ]);

        $withDescription = Project::create([
            'title' => 'Rich Project',
            'summary' => 'Has detail.',
            'description' => "# Heading\n\n**Bold** and *italic*.\n\n| A | B |\n| - | - |\n| 1 | 2 |\n\n```php\necho 'hi';\n```\n\n[Bad link](javascript:alert(1))\n\n<script>alert('xss')</script>",
            'is_published' => true,
        ]);

        $this->get(route('projects.show', $withoutDescription->slug))
            ->assertOk()
            ->assertDontSee('project-description', false);

        $this->get(route('projects.show', $withDescription->slug))
            ->assertOk()
            ->assertSee('<h1>Heading</h1>', false)
            ->assertSee('<strong>Bold</strong>', false)
            ->assertSee('<em>italic</em>', false)
            ->assertSee('<table>', false)
            ->assertSee('<code class="language-php">', false)
            ->assertDontSee('javascript:', false)
            ->assertDontSee("alert('xss')", false);
    }

    public function test_project_cards_link_to_their_detail_pages(): void
    {
        $project = Project::firstWhere('title', 'TaskFlow Pro');

        $this->get('/projects')
            ->assertOk()
            ->assertSee(route('projects.show', $project->slug), false);
    }

    public function test_unpublished_project_detail_page_is_not_accessible(): void
    {
        $project = Project::create([
            'title' => 'Hidden Project',
            'slug' => 'hidden-project',
            'summary' => 'Should not be visible.',
            'is_published' => false,
        ]);

        $this->get(route('projects.show', $project->slug))->assertNotFound();
    }

    public function test_experience_period_is_derived_from_dates(): void
    {
        $this->get('/experience')->assertOk()->assertSee('Present');

        $current = Experience::firstWhere('is_current', true);
        $past = Experience::firstWhere('is_current', false);

        $this->assertStringEndsWith('Present', $current->period);
        $this->assertStringContainsString(' - ', $past->period);
        $this->assertStringNotContainsString('Present', $past->period);
        $this->assertStringContainsString(' - ', $past->period);
    }

    public function test_contact_form_stores_a_message(): void
    {
        $this->post('/contact', [
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
            'subject' => 'Hello',
            'body' => 'Let us build something together.',
        ])->assertRedirect();

        $this->assertDatabaseHas('messages', ['email' => 'ada@example.com']);
    }

    public function test_contact_form_rejects_invalid_input(): void
    {
        $this->post('/contact', ['name' => '', 'email' => 'nope', 'body' => ''])
            ->assertSessionHasErrors(['name', 'email', 'body']);

        $this->assertSame(0, Message::count());
    }

    public function test_contact_form_returns_json_for_ajax_submissions(): void
    {
        $this->postJson('/contact', [
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
            'subject' => 'Hello',
            'body' => 'Let us build something together.',
        ])->assertOk()->assertJson([
            'message' => 'Thanks! Your message has been sent.',
        ]);

        $this->assertDatabaseHas('messages', ['email' => 'ada@example.com']);
    }

    public function test_contact_form_returns_json_validation_errors_for_ajax_submissions(): void
    {
        $this->postJson('/contact', ['name' => '', 'email' => 'nope', 'body' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'body']);

        $this->assertSame(0, Message::count());
    }

    public function test_stats_are_rendered(): void
    {
        $this->assertSame(3, Stat::count());
        $this->get('/')->assertOk()->assertSee('Projects built');
    }
}
