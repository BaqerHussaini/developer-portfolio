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

    public function test_stats_are_rendered(): void
    {
        $this->assertSame(3, Stat::count());
        $this->get('/')->assertOk()->assertSee('Projects built');
    }
}
