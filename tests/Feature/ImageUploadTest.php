<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    private function admin(): User
    {
        return User::factory()->create();
    }

    public function test_admin_can_upload_a_project_image(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/projects', [
            'title' => 'With Image',
            'summary' => 'Uploaded through the admin form.',
            'sort_order' => 1,
            'is_published' => 1,
            'image' => UploadedFile::fake()->create('cover.png', 120, 'image/png'),
        ])->assertRedirect('/admin/projects');

        $project = Project::firstWhere('title', 'With Image');

        $this->assertNotNull($project->image_path);
        Storage::disk('public')->assertExists($project->image_path);

        $this->get('/')->assertOk()->assertSee(Storage::disk('public')->url($project->image_path));
    }

    public function test_uploading_a_new_image_replaces_and_deletes_the_old_file(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/projects', [
            'title' => 'Replaceable',
            'summary' => 'First image.',
            'sort_order' => 1,
            'is_published' => 1,
            'image' => UploadedFile::fake()->create('first.png', 120, 'image/png'),
        ]);

        $project = Project::firstWhere('title', 'Replaceable');
        $first = $project->image_path;
        Storage::disk('public')->assertExists($first);

        $this->put("/admin/projects/{$project->id}", [
            'title' => 'Replaceable',
            'summary' => 'Second image.',
            'sort_order' => 1,
            'is_published' => 1,
            'image' => UploadedFile::fake()->create('second.png', 120, 'image/png'),
        ])->assertRedirect('/admin/projects');

        $second = $project->fresh()->image_path;

        $this->assertNotSame($first, $second);
        Storage::disk('public')->assertMissing($first);
        Storage::disk('public')->assertExists($second);
    }

    public function test_admin_can_delete_a_project_image_from_the_ui(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/projects', [
            'title' => 'Removable',
            'summary' => 'Image gets deleted.',
            'sort_order' => 1,
            'is_published' => 1,
            'image' => UploadedFile::fake()->create('gone.png', 120, 'image/png'),
        ]);

        $project = Project::firstWhere('title', 'Removable');
        Storage::disk('public')->assertExists($project->image_path);

        $this->put("/admin/projects/{$project->id}", [
            'title' => 'Removable',
            'summary' => 'Image gets deleted.',
            'sort_order' => 1,
            'is_published' => 1,
            'remove_image' => 1,
        ])->assertRedirect('/admin/projects');

        $project->refresh();

        $this->assertNull($project->image_path);
        Storage::disk('public')->assertMissing($project->getRawOriginal('image_path'));
    }

    public function test_deleting_a_project_removes_its_image_file(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/projects', [
            'title' => 'Doomed',
            'summary' => 'Deleted entirely.',
            'sort_order' => 1,
            'is_published' => 1,
            'image' => UploadedFile::fake()->create('doomed.png', 120, 'image/png'),
        ]);

        $project = Project::firstWhere('title', 'Doomed');
        $path = $project->image_path;

        $this->delete("/admin/projects/{$project->id}");

        Storage::disk('public')->assertMissing($path);
    }

    public function test_uploaded_image_must_be_an_image_under_four_megabytes(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/projects', [
            'title' => 'Bad upload',
            'summary' => 'Not an image.',
            'sort_order' => 1,
            'image' => UploadedFile::fake()->create('notes.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('image');

        $this->assertSame(0, Project::count());
    }

    public function test_admin_can_upload_and_delete_a_profile_photo(): void
    {
        $this->actingAs($this->admin());

        $this->put('/admin/settings', [
            'settings' => ['logo_initials' => 'BH'],
            'photo' => UploadedFile::fake()->create('me.jpg', 120, 'image/jpeg'),
        ])->assertRedirect();

        $path = Setting::get('profile_photo');

        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);
        $this->get('/')->assertOk()->assertSee(Storage::disk('public')->url($path));

        $this->put('/admin/settings', [
            'settings' => ['logo_initials' => 'BH'],
            'remove_photo' => 1,
        ])->assertRedirect();

        Storage::disk('public')->assertMissing($path);
        $this->assertNull(Setting::get('profile_photo'));
    }
}
