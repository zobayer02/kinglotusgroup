<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\ProspectusSection;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Tests\TestCase;

class ProspectusManagementTest extends TestCase
{
    protected function getAdmin(): Admin
    {
        $admin = Admin::query()->first();
        if (! $admin) {
            $admin = Admin::create([
                'name' => 'Test Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('Admin@12345678'),
                'role' => 'admin',
                'session_version' => 1,
            ]);
        }

        return $admin;
    }

    protected function asAdmin()
    {
        $admin = $this->getAdmin();

        return $this->actingAs($admin, 'admin')
            ->withSession([
                'admin_session_version' => (int) $admin->session_version,
                'admin_last_activity_at' => now()->timestamp,
            ])
            ->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_unauthenticated_users_cannot_update_prospectus(): void
    {
        $response = $this->withoutMiddleware(ValidateCsrfToken::class)
            ->patch(route('admin.content.prospectus.update'), [
                'section_title' => 'Test Title',
            ]);
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_prospectus_in_content_management(): void
    {
        $response = $this->asAdmin()->get(route('admin.content.index'));
        $response->assertStatus(200);
        $response->assertSee('Brochure Directory');
        $response->assertSee('Project Prospectus & Brochure');
    }

    public function test_admin_can_update_prospectus_section(): void
    {
        $response = $this->asAdmin()->patch(route('admin.content.prospectus.update'), [
            'section_title' => 'Updated Prospectus Title',
            'section_subtitle' => 'Updated Subtitle',
            'is_visible' => '1',
            'brochures' => [
                [
                    'title' => 'Flyer Page 1',
                    'subtitle' => 'Exclusive Luxury Living',
                    'image_path' => 'uploads/prospectus/test-page-1.webp',
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.content.index', ['module' => 'prospectus']));
        $response->assertSessionHas('success');

        $section = ProspectusSection::query()->first();
        $this->assertNotNull($section);
        $this->assertEquals('Updated Prospectus Title', $section->section_title);
        $this->assertEquals('Updated Subtitle', $section->section_subtitle);
        $this->assertTrue((bool) $section->is_visible);
        $this->assertCount(1, $section->brochures());
    }

    public function test_homepage_renders_prospectus_section(): void
    {
        ProspectusSection::query()->updateOrCreate(['id' => 1], [
            'section_title' => 'Unique Prospectus Headline',
            'section_subtitle' => 'Sub heading text',
            'is_visible' => true,
            'brochures' => [
                [
                    'title' => 'Sample Flyer Item',
                    'subtitle' => 'Sub text',
                    'image_path' => 'uploads/prospectus/test-page.webp',
                ],
            ],
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Unique Prospectus Headline');
    }

    public function test_homepage_hides_prospectus_when_not_visible(): void
    {
        ProspectusSection::query()->updateOrCreate(['id' => 1], [
            'section_title' => 'Hidden Prospectus Headline',
            'section_subtitle' => 'Sub heading text',
            'is_visible' => false,
            'brochures' => [
                [
                    'title' => 'Hidden Flyer Item',
                    'subtitle' => 'Sub text',
                    'image_path' => 'uploads/prospectus/test-page.webp',
                ],
            ],
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertDontSee('Hidden Prospectus Headline');
    }
}
