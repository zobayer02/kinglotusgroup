<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\ValuedShareholder;
use App\Models\ValuedShareholderSection;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Tests\TestCase;

class ValuedShareholdersManagementTest extends TestCase
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

    public function test_unauthenticated_users_cannot_access_shareholders_api(): void
    {
        $response = $this->get(route('admin.content.valued-shareholders.items'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_search_and_paginate_shareholders(): void
    {
        ValuedShareholder::query()->create([
            'name' => 'Dr. Rafiqul Islam Unique',
            'position' => 'Senior Director',
            'sort_order' => 1,
        ]);

        $response = $this->asAdmin()->getJson(route('admin.content.valued-shareholders.items', [
            'search' => 'Rafiqul Islam Unique',
            'page' => 1,
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'current_page',
            'has_more',
            'total',
        ]);

        $data = $response->json('data');
        $this->assertNotEmpty($data);
        $this->assertEquals('Dr. Rafiqul Islam Unique', $data[0]['name']);
    }

    public function test_admin_can_create_shareholder_with_validation(): void
    {
        // Validation failure
        $failResponse = $this->asAdmin()->postJson(route('admin.content.valued-shareholders.store'), [
            'name' => '',
        ]);
        $failResponse->assertStatus(422);
        $failResponse->assertJsonValidationErrors(['name']);

        // Success creation
        $successResponse = $this->asAdmin()->postJson(route('admin.content.valued-shareholders.store'), [
            'name' => 'Tanvir Ahmed Shareholder',
            'position' => 'Strategic Investor',
        ]);

        $successResponse->assertStatus(201);
        $successResponse->assertJson([
            'success' => true,
            'message' => 'Shareholder added successfully.',
        ]);

        $created = ValuedShareholder::query()->where('name', 'Tanvir Ahmed Shareholder')->first();
        $this->assertNotNull($created);
        $this->assertEquals('Strategic Investor', $created->position);
        $this->assertNull($created->image_path);
    }

    public function test_admin_can_update_shareholder_and_remove_image(): void
    {
        $shareholder = ValuedShareholder::query()->create([
            'name' => 'Initial Name',
            'position' => 'Initial Position',
            'image_path' => 'uploads/valued-shareholders/fake.webp',
        ]);

        $response = $this->asAdmin()->postJson(
            route('admin.content.valued-shareholders.item.update', ['shareholder' => $shareholder->id]),
            [
                'name' => 'Updated Name',
                'position' => 'Chief Technology Investor',
                'remove_image' => '1',
            ]
        );

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Shareholder updated successfully.',
        ]);

        $shareholder->refresh();
        $this->assertEquals('Updated Name', $shareholder->name);
        $this->assertEquals('Chief Technology Investor', $shareholder->position);
        $this->assertNull($shareholder->image_path);
    }

    public function test_admin_can_delete_shareholder(): void
    {
        $shareholder = ValuedShareholder::query()->create([
            'name' => 'To Be Deleted',
            'position' => 'Temporary',
        ]);

        $response = $this->asAdmin()->deleteJson(
            route('admin.content.valued-shareholders.destroy', ['shareholder' => $shareholder->id])
        );

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Shareholder removed successfully.',
        ]);

        $this->assertNull(ValuedShareholder::query()->find($shareholder->id));
    }

    public function test_homepage_renders_valued_shareholders_section(): void
    {
        $section = ValuedShareholderSection::query()->firstOrNew();
        $section->is_visible = true;
        $section->section_title = 'Our Valued Shareholders';
        $section->save();

        ValuedShareholder::query()->create([
            'name' => 'Demo Shareholder For Marquee',
            'position' => 'Board Member',
            'sort_order' => 1,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Our Valued Shareholders');
        $response->assertSee(route('shareholders.index'));
        $response->assertSee('Explore All Shareholders');
    }

    public function test_public_shareholders_directory_page_renders_successfully(): void
    {
        ValuedShareholder::query()->create([
            'name' => 'Directory Test Investor',
            'position' => 'Strategic Partner',
            'sort_order' => 0,
        ]);

        $response = $this->get(route('shareholders.index'));
        $response->assertStatus(200);
        $response->assertSee('Directory Test Investor');
        $response->assertSee('Strategic Partner');
        $response->assertSee('Search by name or position...');
    }

    public function test_public_shareholders_items_endpoint_returns_json_results(): void
    {
        ValuedShareholder::query()->create([
            'name' => 'Public API Shareholder',
            'position' => 'Founding Investor',
            'sort_order' => 2,
        ]);

        $response = $this->getJson(route('shareholders.items', [
            'search' => 'Public API Shareholder',
            'page' => 1,
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'position', 'image_url'],
            ],
            'current_page',
            'has_more',
            'total',
        ]);

        $data = $response->json('data');
        $this->assertNotEmpty($data);
        $this->assertEquals('Public API Shareholder', $data[0]['name']);
        $this->assertEquals('Founding Investor', $data[0]['position']);
    }
}
