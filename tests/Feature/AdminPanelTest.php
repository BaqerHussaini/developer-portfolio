<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'email' => 'engineer.baqer.hussaini@gmail.com',
            'password' => 'secret-password',
        ]);
    }

    public function test_guests_are_redirected_to_the_login_screen(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/projects')->assertRedirect('/admin/login');
    }

    public function test_admin_can_sign_in_with_the_seeded_account(): void
    {
        $this->admin();

        $this->post('/admin/login', [
            'email' => 'engineer.baqer.hussaini@gmail.com',
            'password' => 'secret-password',
        ])->assertRedirect('/admin');

        $this->assertAuthenticated();
    }

    public function test_login_fails_with_a_wrong_password(): void
    {
        $this->admin();

        $this->post('/admin/login', [
            'email' => 'engineer.baqer.hussaini@gmail.com',
            'password' => 'not-the-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_pages_are_reachable_once_signed_in(): void
    {
        $this->actingAs($this->admin());

        foreach ([
            '/admin',
            '/admin/settings',
            '/admin/account',
            '/admin/account/password',
            '/admin/projects',
            '/admin/projects/create',
            '/admin/experiences',
            '/admin/skills',
            '/admin/stats',
            '/admin/messages',
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_admin_can_create_update_and_delete_a_project(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/projects', [
            'title' => 'Laravel Portfolio',
            'category' => 'Web App',
            'summary' => 'Full stack rebuild with PostgreSQL.',
            'description' => 'A longer write-up about the build.',
            'tags' => 'Laravel, PostgreSQL',
            'sort_order' => 1,
            'is_published' => 1,
        ])->assertRedirect('/admin/projects');

        $project = Project::firstWhere('title', 'Laravel Portfolio');

        $this->assertSame(['Laravel', 'PostgreSQL'], $project->tags);
        $this->assertSame('A longer write-up about the build.', $project->description);

        $this->put("/admin/projects/{$project->id}", [
            'title' => 'Laravel Portfolio v2',
            'summary' => 'Updated summary.',
            'description' => 'Updated **description**.',
            'tags' => '',
            'sort_order' => 1,
            'is_published' => 0,
        ])->assertRedirect('/admin/projects');

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'Laravel Portfolio v2',
            'description' => 'Updated **description**.',
            'is_published' => false,
        ]);

        $this->delete("/admin/projects/{$project->id}")->assertRedirect();

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_settings_updates_are_persisted(): void
    {
        $this->actingAs($this->admin());

        $this->put('/admin/settings', [
            'settings' => [
                'site_title' => 'Edited Title',
                'hero_phrases' => ['alpha', 'beta'],
                'hero_metrics' => [['label' => 'Web', 'value' => '99%']],
                'contact_socials' => [['platform' => 'GitHub', 'url' => 'https://github.com/baqer']],
                'about_paragraphs' => ['One.', 'Two.'],
            ],
        ])->assertRedirect();

        $this->assertSame('Edited Title', Setting::get('site_title'));
        $this->assertSame(['alpha', 'beta'], Setting::get('hero_phrases'));
        $this->assertSame([['label' => 'Web', 'value' => '99%']], Setting::get('hero_metrics'));
        $this->assertSame('https://github.com/baqer', Setting::get('contact_socials')[0]['url']);

        $this->get('/')->assertOk()->assertSee('Edited Title');
    }

    public function test_admin_can_manage_contact_messages(): void
    {
        $this->actingAs($this->admin());

        $message = Message::create([
            'name' => 'Grace Hopper',
            'email' => 'grace@example.com',
            'subject' => 'Question',
            'body' => 'Are you available?',
        ]);

        $this->get('/admin/messages')->assertOk()->assertSee('Grace Hopper');

        $this->get("/admin/messages/{$message->id}")->assertOk();
        $this->assertTrue($message->fresh()->is_read);

        $this->patch("/admin/messages/{$message->id}")->assertRedirect();
        $this->assertTrue($message->fresh()->is_archived);

        $this->delete("/admin/messages/{$message->id}")->assertRedirect();
        $this->assertDatabaseMissing('messages', ['id' => $message->id]);
    }

    public function test_line_and_paragraph_settings_stay_split_into_a_list(): void
    {
        $this->actingAs($this->admin());

        $this->put('/admin/settings', [
            'settings' => [
                'hero_phrases' => "digital experiences\r\nmodern products\n\nclean systems\n",
                'about_paragraphs' => "First paragraph line one\nline two.\n\nSecond paragraph.",
            ],
        ])->assertRedirect();

        $this->assertSame(
            ['digital experiences', 'modern products', 'clean systems'],
            Setting::get('hero_phrases')
        );

        $this->assertSame(
            ['First paragraph line one line two.', 'Second paragraph.'],
            Setting::get('about_paragraphs')
        );
    }

    public function test_a_single_newline_joined_phrase_value_is_repaired_for_display(): void
    {
        Setting::updateOrCreate(
            ['key' => 'hero_phrases'],
            ['group' => 'hero', 'value' => "digital experiences\nmodern products\nclean systems"]
        );

        $this->get('/')->assertOk();

        $this->assertSame(
            ['digital experiences', 'modern products', 'clean systems'],
            \App\Support\Portfolio::content()['hero']['phrases']
        );
    }

    public function test_admin_can_update_account_details(): void
    {
        $user = $this->admin();
        $this->actingAs($user);

        $this->put('/admin/account', [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ])->assertRedirect()->assertSessionHas('status');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    public function test_admin_can_change_password_with_the_current_password(): void
    {
        $user = $this->admin();
        $this->actingAs($user);

        $this->put('/admin/account/password', [
            'current_password' => 'secret-password',
            'password' => 'new-secret-password',
            'password_confirmation' => 'new-secret-password',
        ])->assertRedirect('/admin/account');

        $this->assertTrue(Hash::check('new-secret-password', $user->fresh()->password));
    }

    public function test_password_change_requires_the_current_password(): void
    {
        $user = $this->admin();
        $this->actingAs($user);

        $this->put('/admin/account/password', [
            'password' => 'new-secret-password',
            'password_confirmation' => 'new-secret-password',
        ])->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('secret-password', $user->fresh()->password));
    }

    public function test_unchanged_settings_flashes_nothing_updated(): void
    {
        $this->actingAs($this->admin());

        $this->put('/admin/settings', [
            'settings' => [
                'site_title' => Setting::defaults()['site_title']['value'],
            ],
        ])->assertRedirect()->assertSessionHas('status', 'Nothing updated.');
    }

    public function test_unchanged_project_flashes_nothing_updated(): void
    {
        $this->actingAs($this->admin());

        $project = Project::create([
            'title' => 'Existing project',
            'slug' => 'existing-project',
            'summary' => 'Existing summary.',
            'tags' => [],
            'is_published' => true,
            'is_featured' => false,
            'sort_order' => 1,
        ]);

        $this->put("/admin/projects/{$project->id}", [
            'title' => 'Existing project',
            'slug' => 'existing-project',
            'summary' => 'Existing summary.',
            'tags' => '',
            'is_published' => 1,
            'is_featured' => 0,
            'sort_order' => 1,
        ])->assertRedirect('/admin/projects')->assertSessionHas('status', 'Nothing updated.');
    }

    public function test_unchanged_skill_stat_and_experience_flash_nothing_updated(): void
    {
        $this->actingAs($this->admin());

        $skill = \App\Models\Skill::create([
            'name' => 'Laravel',
            'level' => 80,
            'category' => 'Backend',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $this->put("/admin/skills/{$skill->id}", [
            'name' => 'Laravel',
            'level' => 80,
            'category' => 'Backend',
            'is_published' => 1,
            'sort_order' => 1,
        ])->assertRedirect('/admin/skills')->assertSessionHas('status', 'Nothing updated.');

        $stat = \App\Models\Stat::create([
            'label' => 'Projects',
            'value' => '10+',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $this->put("/admin/stats/{$stat->id}", [
            'label' => 'Projects',
            'value' => '10+',
            'is_published' => 1,
            'sort_order' => 1,
        ])->assertRedirect('/admin/stats')->assertSessionHas('status', 'Nothing updated.');

        $experience = \App\Models\Experience::create([
            'company' => 'Acme',
            'role' => 'Engineer',
            'start_date' => '2024-01-01',
            'is_current' => true,
            'description' => 'Building things.',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $this->put("/admin/experiences/{$experience->id}", [
            'company' => 'Acme',
            'role' => 'Engineer',
            'start_date' => '2024-01-01',
            'is_current' => 1,
            'description' => 'Building things.',
            'is_published' => 1,
            'sort_order' => 1,
        ])->assertRedirect('/admin/experiences')->assertSessionHas('status', 'Nothing updated.');
    }

    public function test_unchanged_account_flashes_nothing_updated(): void
    {
        $user = $this->admin();
        $this->actingAs($user);

        $this->put('/admin/account', [
            'name' => $user->name,
            'email' => $user->email,
        ])->assertRedirect()->assertSessionHas('status', 'Nothing updated.');
    }

    public function test_admin_can_sign_out(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/logout')->assertRedirect('/admin/login');

        $this->assertGuest();
    }
}
