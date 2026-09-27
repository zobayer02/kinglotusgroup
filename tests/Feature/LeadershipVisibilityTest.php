<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\LeadershipSection;
use App\Models\ValuedShareholderSection;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Tests\TestCase;

class LeadershipVisibilityTest extends TestCase
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

    public function test_guest_cannot_toggle_leadership_visibility(): void
    {
        $response = $this->patch(route('admin.content.leadership.toggle-visibility'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_toggle_leadership_visibility_via_json(): void
    {
        $section = LeadershipSection::query()->firstOrNew();
        $section->section_title = 'Board of Directors';
        $section->founder_name = 'Test Founder';
        $section->is_visible = true;
        $section->save();

        $response = $this->asAdmin()->patchJson(route('admin.content.leadership.toggle-visibility'), [
            'is_visible' => false,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'is_visible' => false,
                'should_display' => false,
                'status_text' => 'Hidden on website',
            ]);

        $this->assertFalse($section->fresh()->is_visible);

        // Turn back on
        $response2 = $this->asAdmin()->patchJson(route('admin.content.leadership.toggle-visibility'), [
            'is_visible' => true,
        ]);

        $response2->assertStatus(200)
            ->assertJson([
                'success' => true,
                'is_visible' => true,
                'should_display' => true,
                'status_text' => 'Visible on website',
            ]);

        $this->assertTrue($section->fresh()->is_visible);
    }

    public function test_hidden_leadership_section_does_not_render_on_homepage(): void
    {
        $section = LeadershipSection::query()->firstOrNew();
        $section->section_title = 'Board Members Unique Test';
        $section->founder_name = 'Chairman John Doe';
        $section->is_visible = false;
        $section->save();
        \App\Support\SiteCache::forgetPublicPages();

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertDontSee('Board Members Unique Test');
        $response->assertDontSee('Chairman John Doe');

        // Now set visible via the toggle endpoint
        $this->asAdmin()->patchJson(route('admin.content.leadership.toggle-visibility'), [
            'is_visible' => true,
        ]);

        $responseVisible = $this->get('/');
        $responseVisible->assertStatus(200);
        $responseVisible->assertSee('Board Members Unique Test');
        $responseVisible->assertSee('Chairman John Doe');
    }

    public function test_admin_can_toggle_valued_shareholders_visibility_via_json(): void
    {
        $section = ValuedShareholderSection::query()->firstOrNew();
        $section->section_title = 'Our Shareholders Test';
        $section->is_visible = true;
        $section->save();

        $response = $this->asAdmin()->patchJson(route('admin.content.valued-shareholders.update'), [
            'shareholder_section_visible' => false,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'is_visible' => false,
                'status_text' => 'Hidden on website',
            ]);

        $this->assertFalse($section->fresh()->is_visible);
    }

    public function test_admin_can_toggle_board_members_slider_visibility_via_json(): void
    {
        $section = LeadershipSection::query()->firstOrNew();
        $section->board_members_visible = true;
        $section->board_members = [
            ['name' => 'MD. Nurul Amin', 'position' => 'Sales & Marketing Director', 'image_path' => ''],
        ];
        $section->save();

        // 1-Click Toggle to hide
        $response = $this->asAdmin()->patchJson(route('admin.content.leadership.toggle-board-members'), [
            'board_members_visible' => false,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'is_visible' => false,
                'should_display' => false,
                'status_text' => 'Hidden on website',
            ]);

        $this->assertFalse($section->fresh()->board_members_visible);

        // 1-Click Toggle to show
        $response2 = $this->asAdmin()->patchJson(route('admin.content.leadership.toggle-board-members'), [
            'board_members_visible' => true,
        ]);

        $response2->assertStatus(200)
            ->assertJson([
                'success' => true,
                'is_visible' => true,
                'should_display' => true,
                'status_text' => 'Visible on website',
            ]);

        $this->assertTrue($section->fresh()->board_members_visible);
    }

    public function test_founder_remains_visible_while_board_members_are_hidden_on_homepage(): void
    {
        $section = LeadershipSection::query()->firstOrNew();
        $section->section_title = 'Board of Directors';
        $section->founder_name = 'MD. Mizanur Rahman';
        $section->founder_position = 'Managing Director & CEO';
        $section->board_members = [
            ['name' => 'MD. Nurul Amin Secret Director', 'position' => 'Sales & Marketing Director', 'image_path' => ''],
        ];
        $section->is_visible = true;
        $section->board_members_visible = false;
        $section->save();
        \App\Support\SiteCache::forgetPublicPages();

        $response = $this->get('/');
        $response->assertStatus(200);

        // Top Founder part MUST be visible
        $response->assertSee('Board of Directors');
        $response->assertSee('MD. Mizanur Rahman');
        $response->assertSee('Managing Director & CEO');

        // Bottom Board Members cards MUST NOT be visible
        $response->assertDontSee('MD. Nurul Amin Secret Director');

        // Now toggle board members visible with 1-click
        $this->asAdmin()->patchJson(route('admin.content.leadership.toggle-board-members'), [
            'board_members_visible' => true,
        ]);

        $response2 = $this->get('/');
        $response2->assertStatus(200);
        $response2->assertSee('MD. Mizanur Rahman');
        $response2->assertSee('MD. Nurul Amin Secret Director');
    }

    public function test_secondary_leader_card_is_optional_and_flipped_when_present(): void
    {
        $section = LeadershipSection::query()->firstOrNew();
        $section->section_title = 'Board of Directors';
        $section->founder_name = 'MD. Mizanur Rahman';
        $section->founder_position = 'Founder & CEO';
        $section->is_visible = true;
        // Initially no secondary leader
        $section->secondary_leader_name = null;
        $section->secondary_leader_position = null;
        $section->secondary_leader_description = null;
        $section->secondary_leader_image_path = null;
        $section->save();
        \App\Support\SiteCache::forgetPublicPages();

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('MD. Mizanur Rahman');
        $response->assertDontSee('class="leadership-founder leadership-founder--flipped"', false);
        $response->assertDontSee('Shahadat Hossain');
        $response->assertDontSee('Co-Founder & Vice Chairman');

        // Now add secondary leader
        $section->secondary_leader_name = 'Shahadat Hossain';
        $section->secondary_leader_position = 'Co-Founder & Vice Chairman';
        $section->secondary_leader_description = 'Overseeing corporate operations and group vision.';
        $section->save();
        \App\Support\SiteCache::forgetPublicPages();

        $responseWithSecond = $this->get('/');
        $responseWithSecond->assertStatus(200);
        $responseWithSecond->assertSee('MD. Mizanur Rahman');
        $responseWithSecond->assertSee('Shahadat Hossain');
        $responseWithSecond->assertSee('Co-Founder & Vice Chairman');
        $responseWithSecond->assertSee('Overseeing corporate operations and group vision.');
        $responseWithSecond->assertSee('class="leadership-founder leadership-founder--flipped"', false);
    }

    public function test_admin_can_save_and_update_secondary_leader_details(): void
    {
        $response = $this->asAdmin()->patch(route('admin.content.leadership.update'), [
            'section_title' => 'Leadership Board',
            'founder_name' => 'MD. Mizanur Rahman',
            'founder_position' => 'Founder & CEO',
            'secondary_leader_name' => 'Dr. Farhana Islam',
            'secondary_leader_position' => 'Co-Founder & Managing Director',
            'secondary_leader_description' => 'Guiding strategic investments and development.',
            'is_visible' => '1',
            'board_members_visible' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $section = LeadershipSection::query()->first();
        $this->assertNotNull($section);
        $this->assertSame('Dr. Farhana Islam', $section->secondary_leader_name);
        $this->assertSame('Co-Founder & Managing Director', $section->secondary_leader_position);
        $this->assertSame('Guiding strategic investments and development.', $section->secondary_leader_description);
        $this->assertTrue($section->hasSecondaryLeader());
    }
}

